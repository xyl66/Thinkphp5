/**
 * Created by F3233253 on 2016/11/3.
 */
function fillZero(v) {
    if (v < 10) { v = '0' + v; }
    return v;
}

function getStatus(status) {
    return status ? '启用' : '禁用';
}
//定义全局缓存
var course_edit_cache = {
    title: "",
    name: "",
    condition: "",
};
//缓存赋值
function value2cache(cache, value) {
    cache.title = value['title'];
    cache.name = value['name'];
    cache.condition = value['condition'];
}

function cache2value(value, cache) {
    value['title'] = cache.title;
    value['name'] = cache.name;
    value['condition'] = cache.condition;
}
var boot = function(currentPage, totalPages) {
    $('#example').bootstrapPaginator({
        currentPage: currentPage, //当前页
        totalPages: totalPages, //总页数
        bootstrapMajorVersion: 3, //兼容Bootstrap3.x版本
        tooltipTitles: function(type, page) {
            switch (type) {
                case "first":
                    return "第一页";
                case "prev":
                    return "上一页";
                case "next":
                    return "下一页";
                case "last":
                    return "最后一页";
                case "page":
                    return page;
            }
            return "";
        },
        onPageClicked: function(event, originalEvent, type, page) {
            $("#currentPage").val(page).show().focus().blur().hide();
            /*$.get( '/Home/GetPaginationData' , { currentPage: page, pageSize:10 }, function (view) {
                $( '#tableTest' ).html(view);
            });*/
        }
    });
}

//编辑组件
Vue.component('my-component', {
    template: '<div>A custom component!</div>'
})
var vm = new Vue({
    el: '#app',
    data: {
        selectdate: parseInt(new Date().valueOf() / 1000),
        search: "", //搜索
        currentPage: 1,
        pageSize: 10, //每页显示个数
        isget: 0,
        list: {},
        todos: {},
        warn: { //提示数据
            title: false,
            name: false,
            condition: false,
        },
    },
    computed: {
        todos: function() {
            var cpage = this.currentPage,
                copyArr, pageSize = this.pageSize,
                count = 0;
            var sq = this.selectdate;
            var q = this.filteredList;
            var curTodo = todo = q == undefined ? {} : q;
            //分页 每页数据pageSize
            copyArr = todo.length > 0 ? todo.slice() : {};
            while (copyArr.length > pageSize) {
                curTodo = copyArr.splice(0, pageSize);
                if (++count == cpage) {
                    break
                }
            }
            if (count != cpage) {
                curTodo = copyArr;
            };
            if (q != undefined && q.length > 0) {
                var totalPage = Math.ceil(todo.length / pageSize); //总页数
                if (totalPage > 0) {
                    boot(cpage, totalPage);
                }
            }
            return curTodo;
        },
        filteredList: function() {
            var filter = Vue.filter('filterBy')
            return filter(this.list, this.search, 'user_school');
        },
        filterCourse: function(courselist) {
            var filter = Vue.filter('filterBy')
            return filter(courselist, this.search, 'user_school');
        }
    },
    ready: function() {
        var dd = new Date(),
            y = dd.getFullYear(),
            m = dd.getMonth() + 1,
            d = dd.getDate();
        var date = y + "-";
        date = date + (m > 10 ? m : "0" + m);
        date = date + "-" + (d > 10 ? d : "0" + d);
        var course_time = Date.parse(date) / 1000;
        $.ajax({
            url: indexURL,
            type: 'post',
            dataType: 'json',
            success: function(resdata) {
                vm.$data['list'] = resdata;
            }
        });
    },
    filters: {
        getSexs: function(sex) {
            return sex == "woman" ? '女' : '男';
        },
        getStatusBtn: function(type) {
            return type ? '禁用' : '启用';
        }
    },
    methods: {
        export_file: function(index_id) {
            $.ajax({
                url: export_file_excelURL,
                type: 'post',
                dataType: 'json',
                success: function(result) {
                    if (result.status == 'success') {
                        /* window.open(result.url);*/
                        var form = $("<form>"); //定义一个form表单
                        form.attr("style", "display:none");
                        form.attr("target", "");
                        form.attr("method", "post");
                        form.attr("action", result.url);
                        var input1 = $("<input>");
                        input1.attr("type", "hidden");
                        input1.attr("name", 'course_id');
                        input1.attr("value", result.data);
                        $("body").append(form); //将表单放置在web中
                        form.append(input1);
                        form.submit(); //表单提交
                    } else {
                        $.alert({
                            title: '课程签到系统',
                            type: 'red',
                            content: result.msg,
                        });
                    }
                }
            });
        },
    }
})