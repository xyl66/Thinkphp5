<?php

//@ÉîÛÚPHP¿ª·¢¹¤×÷ÊÒ
//QQ£º771943372
//Ê±¼ä£º2013Äê5ÔÂ

require_once dirname(__FILE__) . '/config/tcpdf_config.php';
require_once dirname(__FILE__) . '/unicode_data.php';
require_once dirname(__FILE__) . '/htmlcolors.php';

if (!class_exists('TCPDF', false)) {
	define('PDF_PRODUCER', 'TCPDF 4.8.009 (http://www.tcpdf.org)');
	class TCPDF
	{
		/**
		* @var current page number
		* @access protected
		*/
		protected $page;
		/**
		* @var current object number
		* @access protected
		*/
		protected $n;
		/**
		* @var array of object offsets
		* @access protected
		*/
		protected $offsets;
		/**
		* @var buffer holding in-memory PDF
		* @access protected
		*/
		protected $buffer;
		/**
		* @var array containing pages
		* @access protected
		*/
		protected $pages = array();
		/**
		* @var current document state
		* @access protected
		*/
		protected $state;
		/**
		* @var compression flag
		* @access protected
		*/
		protected $compress;
		/**
		* @var current page orientation (P = Portrait, L = Landscape)
		* @access protected
		*/
		protected $CurOrientation;
		/**
		* @var array that stores page dimensions and graphic status.<ul><li>$this->pagedim[$this->page]['w'] => page_width_in_points</li><li>$this->pagedim[$this->page]['h'] => height in points</li><li>$this->pagedim[$this->page]['wk'] => page_width_in_points</li><li>$this->pagedim[$this->page]['hk'] => height</li><li>$this->pagedim[$this->page]['tm'] => top_margin</li><li>$this->pagedim[$this->page]['bm'] => bottom_margin</li><li>$this->pagedim[$this->page]['lm'] => left_margin</li><li>$this->pagedim[$this->page]['rm'] => right_margin</li><li>$this->pagedim[$this->page]['pb'] => auto_page_break</li><li>$this->pagedim[$this->page]['or'] => page_orientation</li><li>$this->pagedim[$this->page]['olm'] => original_left_margin</li><li>$this->pagedim[$this->page]['orm'] => original_right_margin</li></ul>
		* @access protected
		*/
		protected $pagedim = array();
		/**
		* @var scale factor (number of points in user unit)
		* @access protected
		*/
		protected $k;
		/**
		* @var width of page format in points
		* @access protected
		*/
		protected $fwPt;
		/**
		* @var height of page format in points
		* @access protected
		*/
		protected $fhPt;
		/**
		* @var current width of page in points
		* @access protected
		*/
		protected $wPt;
		/**
		* @var current height of page in points
		* @access protected
		*/
		protected $hPt;
		/**
		* @var current width of page in user unit
		* @access protected
		*/
		protected $w;
		/**
		* @var current height of page in user unit
		* @access protected
		*/
		protected $h;
		/**
		* @var left margin
		* @access protected
		*/
		protected $lMargin;
		/**
		* @var top margin
		* @access protected
		*/
		protected $tMargin;
		/**
		* @var right margin
		* @access protected
		*/
		protected $rMargin;
		/**
		* @var page break margin
		* @access protected
		*/
		protected $bMargin;
		/**
		* @var cell internal padding
		* @access protected
		*/
		public $cMargin;
		/**
		* @var cell internal padding (previous value)
		* @access protected
		*/
		protected $oldcMargin;
		/**
		* @var current horizontal position in user unit for cell positioning
		* @access protected
		*/
		protected $x;
		/**
		* @var current vertical position in user unit for cell positioning
		* @access protected
		*/
		protected $y;
		/**
		* @var height of last cell printed
		* @access protected
		*/
		protected $lasth;
		/**
		* @var line width in user unit
		* @access protected
		*/
		protected $LineWidth;
		/**
		* @var array of standard font names
		* @access protected
		*/
		protected $CoreFonts;
		/**
		* @var array of used fonts
		* @access protected
		*/
		protected $fonts = array();
		/**
		* @var array of font files
		* @access protected
		*/
		protected $FontFiles = array();
		/**
		* @var array of encoding differences
		* @access protected
		*/
		protected $diffs = array();
		/**
		* @var array of used images
		* @access protected
		*/
		protected $images = array();
		/**
		* @var array of Annotations in pages
		* @access protected
		*/
		protected $PageAnnots = array();
		/**
		* @var array of internal links
		* @access protected
		*/
		protected $links = array();
		/**
		* @var current font family
		* @access protected
		*/
		protected $FontFamily;
		/**
		* @var current font style
		* @access protected
		*/
		protected $FontStyle;
		/**
		* @var current font ascent (distance between font top and baseline)
		* @access protected
		* @since 2.8.000 (2007-03-29)
		*/
		protected $FontAscent;
		/**
		* @var current font descent (distance between font bottom and baseline)
		* @access protected
		* @since 2.8.000 (2007-03-29)
		*/
		protected $FontDescent;
		/**
		* @var underlining flag
		* @access protected
		*/
		protected $underline;
		/**
		* @var current font info
		* @access protected
		*/
		protected $CurrentFont;
		/**
		* @var current font size in points
		* @access protected
		*/
		protected $FontSizePt;
		/**
		* @var current font size in user unit
		* @access protected
		*/
		protected $FontSize;
		/**
		* @var commands for drawing color
		* @access protected
		*/
		protected $DrawColor;
		/**
		* @var commands for filling color
		* @access protected
		*/
		protected $FillColor;
		/**
		* @var commands for text color
		* @access protected
		*/
		protected $TextColor;
		/**
		* @var indicates whether fill and text colors are different
		* @access protected
		*/
		protected $ColorFlag;
		/**
		* @var automatic page breaking
		* @access protected
		*/
		protected $AutoPageBreak;
		/**
		* @var threshold used to trigger page breaks
		* @access protected
		*/
		protected $PageBreakTrigger;
		/**
		* @var flag set when processing footer
		* @access protected
		*/
		protected $InFooter = false;
		/**
		* @var zoom display mode
		* @access protected
		*/
		protected $ZoomMode;
		/**
		* @var layout display mode
		* @access protected
		*/
		protected $LayoutMode;
		/**
		* @var title
		* @access protected
		*/
		protected $title = '';
		/**
		* @var subject
		* @access protected
		*/
		protected $subject = '';
		/**
		* @var author
		* @access protected
		*/
		protected $author = '';
		/**
		* @var keywords
		* @access protected
		*/
		protected $keywords = '';
		/**
		* @var creator
		* @access protected
		*/
		protected $creator = '';
		/**
		* @var alias for total number of pages
		* @access protected
		*/
		protected $AliasNbPages = '{nb}';
		/**
		* @var alias for page number
		* @access protected
		*/
		protected $AliasNumPage = '{pnb}';
		/**
		* @var right-bottom corner X coordinate of inserted image
		* @since 2002-07-31
		* @author Nicola Asuni
		* @access protected
		*/
		protected $img_rb_x;
		/**
		* @var right-bottom corner Y coordinate of inserted image
		* @since 2002-07-31
		* @author Nicola Asuni
		* @access protected
		*/
		protected $img_rb_y;
		/**
		* @var adjusting factor to convert pixels to user units.
		* @since 2004-06-14
		* @author Nicola Asuni
		* @access protected
		*/
		protected $imgscale = 1;
		/**
		* @var boolean set to true when the input text is unicode (require unicode fonts)
		* @since 2005-01-02
		* @author Nicola Asuni
		* @access protected
		*/
		protected $isunicode = false;
		/**
		* @var PDF version
		* @since 1.5.3
		* @access protected
		*/
		protected $PDFVersion = '1.7';
		/**
		 * @var Minimum distance between header and top page margin.
		 * @access protected
		 */
		protected $header_margin;
		/**
		 * @var Minimum distance between footer and bottom page margin.
		 * @access protected
		 */
		protected $footer_margin;
		/**
		 * @var original left margin value
		 * @access protected
		 * @since 1.53.0.TC013
		 */
		protected $original_lMargin;
		/**
		 * @var original right margin value
		 * @access protected
		 * @since 1.53.0.TC013
		 */
		protected $original_rMargin;
		/**
		 * @var Header font.
		 * @access protected
		 */
		protected $header_font;
		/**
		 * @var Footer font.
		 * @access protected
		 */
		protected $footer_font;
		/**
		 * @var Language templates.
		 * @access protected
		 */
		protected $l;
		/**
		 * @var Barcode to print on page footer (only if set).
		 * @access protected
		 */
		protected $barcode = false;
		/**
		 * @var If true prints header
		 * @access protected
		 */
		protected $print_header = true;
		/**
		 * @var If true prints footer.
		 * @access protected
		 */
		protected $print_footer = true;
		/**
		 * @var Header image logo.
		 * @access protected
		 */
		protected $header_logo = '';
		/**
		 * @var Header image logo width in mm.
		 * @access protected
		 */
		protected $header_logo_width = 30;
		/**
		 * @var String to print as title on document header.
		 * @access protected
		 */
		protected $header_title = '';
		/**
		 * @var String to print on document header.
		 * @access protected
		 */
		protected $header_string = '';
		/**
		 * @var Default number of columns for html table.
		 * @access protected
		 */
		protected $default_table_columns = 4;
		/**
		 * @var HTML PARSER: array to store current link and rendering styles.
		 * @access protected
		 */
		protected $HREF = array();
		/**
		 * @var store a list of available fonts on filesystem.
		 * @access protected
		 */
		protected $fontlist = array();
		/**
		 * @var current foreground color
		 * @access protected
		 */
		protected $fgcolor;
		/**
		 * @var HTML PARSER: array of boolean values, true in case of ordered list (OL), false otherwise.
		 * @access protected
		 */
		protected $listordered = array();
		/**
		 * @var HTML PARSER: array count list items on nested lists.
		 * @access protected
		 */
		protected $listcount = array();
		/**
		 * @var HTML PARSER: current list nesting level.
		 * @access protected
		 */
		protected $listnum = 0;
		/**
		 * @var HTML PARSER: indent amount for lists.
		 * @access protected
		 */
		protected $listindent;
		/**
		 * @var current background color
		 * @access protected
		 */
		protected $bgcolor;
		/**
		 * @var Store temporary font size in points.
		 * @access protected
		 */
		protected $tempfontsize = 10;
		/**
		 * @var spacer for LI tags.
		 * @access protected
		 */
		protected $lispacer = '';
		/**
		 * @var default encoding
		 * @access protected
		 * @since 1.53.0.TC010
		 */
		protected $encoding = 'UTF-8';
		/**
		 * @var PHP internal encoding
		 * @access protected
		 * @since 1.53.0.TC016
		 */
		protected $internal_encoding;
		/**
		 * @var indicates if the document language is Right-To-Left
		 * @access protected
		 * @since 2.0.000
		 */
		protected $rtl = false;
		/**
		 * @var used to force RTL or LTR string inversion
		 * @access protected
		 * @since 2.0.000
		 */
		protected $tmprtl = false;
		/**
		 * Indicates whether document is protected
		 * @access protected
		 * @since 2.0.000 (2008-01-02)
		 */
		protected $encrypted;
		/**
		 * U entry in pdf document
		 * @access protected
		 * @since 2.0.000 (2008-01-02)
		 */
		protected $Uvalue;
		/**
		 * O entry in pdf document
		 * @access protected
		 * @since 2.0.000 (2008-01-02)
		 */
		protected $Ovalue;
		/**
		 * P entry in pdf document
		 * @access protected
		 * @since 2.0.000 (2008-01-02)
		 */
		protected $Pvalue;
		/**
		 * encryption object id
		 * @access protected
		 * @since 2.0.000 (2008-01-02)
		 */
		protected $enc_obj_id;
		/**
		 * last RC4 key encrypted (cached for optimisation)
		 * @access protected
		 * @since 2.0.000 (2008-01-02)
		 */
		protected $last_rc4_key;
		/**
		 * last RC4 computed key
		 * @access protected
		 * @since 2.0.000 (2008-01-02)
		 */
		protected $last_rc4_key_c;
		/**
		 * RC4 padding
		 * @access protected
		 */
		protected $padding = "(¿N^NuŠAd\0NVÿ?..\0¶Ðh>€/©þdSiz";
		/**
		 * RC4 encryption key
		 * @access protected
		 */
		protected $encryption_key;
		/**
		 * Outlines for bookmark
		 * @access protected
		 * @since 2.1.002 (2008-02-12)
		 */
		protected $outlines = array();
		/**
		 * Outline root for bookmark
		 * @access protected
		 * @since 2.1.002 (2008-02-12)
		 */
		protected $OutlineRoot;
		/**
		 * javascript code
		 * @access protected
		 * @since 2.1.002 (2008-02-12)
		 */
		protected $javascript = '';
		/**
		 * javascript counter
		 * @access protected
		 * @since 2.1.002 (2008-02-12)
		 */
		protected $n_js;
		/**
		 * line trough state
		 * @access protected
		 * @since 2.8.000 (2008-03-19)
		 */
		protected $linethrough;
		/**
		 * If true enables user's rights on PDF reader
		 * @access protected
		 * @since 2.9.000 (2008-03-26)
		 */
		protected $ur;
		/**
		 * Names specifying additional document-wide usage rights for the document.
		 * @access protected
		 * @since 2.9.000 (2008-03-26)
		 */
		protected $ur_document;
		/**
		 * Names specifying additional annotation-related usage rights for the document.
		 * @access protected
		 * @since 2.9.000 (2008-03-26)
		 */
		protected $ur_annots;
		/**
		 * Names specifying additional form-field-related usage rights for the document.
		 * @access protected
		 * @since 2.9.000 (2008-03-26)
		 */
		protected $ur_form;
		/**
		 * Names specifying additional signature-related usage rights for the document.
		 * @access protected
		 * @since 2.9.000 (2008-03-26)
		 */
		protected $ur_signature;
		/**
		 * Dot Per Inch Document Resolution (do not change)
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $dpi = 72;
		/**
		 * Array of page numbers were a new page group was started
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $newpagegroup = array();
		/**
		 * Contains the number of pages of the groups
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $pagegroups;
		/**
		 * Contains the alias of the current page group
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $currpagegroup;
		/**
		 * Restrict the rendering of some elements to screen or printout.
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $visibility = 'all';
		/**
		 * Print visibility.
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $n_ocg_print;
		/**
		 * View visibility.
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $n_ocg_view;
		/**
		 * Array of transparency objects and parameters.
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $extgstates;
		/**
		 * Set the default JPEG compression quality (1-100)
		 * @access protected
		 * @since 3.0.000 (2008-03-27)
		 */
		protected $jpeg_quality;
		/**
		 * Default cell height ratio.
		 * @access protected
		 * @since 3.0.014 (2008-05-23)
		 */
		protected $cell_height_ratio = K_CELL_HEIGHT_RATIO;
		/**
		 * PDF viewer preferences.
		 * @access protected
		 * @since 3.1.000 (2008-06-09)
		 */
		protected $viewer_preferences;
		/**
		 * A name object specifying how the document should be displayed when opened.
		 * @access protected
		 * @since 3.1.000 (2008-06-09)
		 */
		protected $PageMode;
		/**
		 * Array for storing gradient information.
		 * @access protected
		 * @since 3.1.000 (2008-06-09)
		 */
		protected $gradients = array();
		/**
		 * Array used to store positions inside the pages buffer.
		 * keys are the page numbers
		 * @access protected
		 * @since 3.2.000 (2008-06-26)
		 */
		protected $intmrk = array();
		/**
		 * Array used to store content positions inside the pages buffer.
		 * keys are the page numbers
		 * @access protected
		 * @since 4.6.021 (2009-07-20)
		 */
		protected $cntmrk = array();
		/**
		 * Array used to store footer positions of each page.
		 * @access protected
		 * @since 3.2.000 (2008-07-01)
		 */
		protected $footerpos = array();
		/**
		 * Array used to store footer lenght of each page.
		 * @access protected
		 * @since 4.0.014 (2008-07-29)
		 */
		protected $footerlen = array();
		/**
		 * True if a newline is created.
		 * @access protected
		 * @since 3.2.000 (2008-07-01)
		 */
		protected $newline = true;
		/**
		 * End position of the latest inserted line
		 * @access protected
		 * @since 3.2.000 (2008-07-01)
		 */
		protected $endlinex = 0;
		/**
		 * PDF string for last line width
		 * @access protected
		 * @since 4.0.006 (2008-07-16)
		 */
		protected $linestyleWidth = '';
		/**
		 * PDF string for last line width
		 * @access protected
		 * @since 4.0.006 (2008-07-16)
		 */
		protected $linestyleCap = '0 J';
		/**
		 * PDF string for last line width
		 * @access protected
		 * @since 4.0.006 (2008-07-16)
		 */
		protected $linestyleJoin = '0 j';
		/**
		 * PDF string for last line width
		 * @access protected
		 * @since 4.0.006 (2008-07-16)
		 */
		protected $linestyleDash = '[] 0 d';
		/**
		 * True if marked-content sequence is open
		 * @access protected
		 * @since 4.0.013 (2008-07-28)
		 */
		protected $openMarkedContent = false;
		/**
		 * Count the latest inserted vertical spaces on HTML
		 * @access protected
		 * @since 4.0.021 (2008-08-24)
		 */
		protected $htmlvspace = 0;
		/**
		 * Array of Spot colors
		 * @access protected
		 * @since 4.0.024 (2008-09-12)
		 */
		protected $spot_colors = array();
		/**
		 * Symbol used for HTML unordered list items
		 * @access protected
		 * @since 4.0.028 (2008-09-26)
		 */
		protected $lisymbol = '';
		/**
		 * String used to mark the beginning and end of EPS image blocks
		 * @access protected
		 * @since 4.1.000 (2008-10-18)
		 */
		protected $epsmarker = 'x#!#EPS#!#x';
		/**
		 * Array of transformation matrix
		 * @access protected
		 * @since 4.2.000 (2008-10-29)
		 */
		protected $transfmatrix = array();
		/**
		 * Current key for transformation matrix
		 * @access protected
		 * @since 4.8.005 (2009-09-17)
		 */
		protected $transfmatrix_key = 0;
		/**
		 * Booklet mode for double-sided pages
		 * @access protected
		 * @since 4.2.000 (2008-10-29)
		 */
		protected $booklet = false;
		/**
		 * Epsilon value used for float calculations
		 * @access protected
		 * @since 4.2.000 (2008-10-29)
		 */
		protected $feps = 0.005;
		/**
		 * Array used for custom vertical spaces for HTML tags
		 * @access protected
		 * @since 4.2.001 (2008-10-30)
		 */
		protected $tagvspaces = array();
		/**
		 * @var HTML PARSER: custom indent amount for lists.
		 * Negative value means disabled.
		 * @access protected
		 * @since 4.2.007 (2008-11-12)
		 */
		protected $customlistindent = -1;
		/**
		 * @var if true keeps the border open for the cell sides that cross the page.
		 * @access protected
		 * @since 4.2.010 (2008-11-14)
		 */
		protected $opencell = true;
		/**
		 * @var array of files to embedd
		 * @access protected
		 * @since 4.4.000 (2008-12-07)
		 */
		protected $embeddedfiles = array();
		/**
		 * @var boolean true when inside html pre tag
		 * @access protected
		 * @since 4.4.001 (2008-12-08)
		 */
		protected $premode = false;
		/**
		 * Array used to store positions of graphics transformation blocks inside the page buffer.
		 * keys are the page numbers
		 * @access protected
		 * @since 4.4.002 (2008-12-09)
		 */
		protected $transfmrk = array();
		/**
		 * Default color for html links
		 * @access protected
		 * @since 4.4.003 (2008-12-09)
		 */
		protected $htmlLinkColorArray = array(0, 0, 255);
		/**
		 * Default font style to add to html links
		 * @access protected
		 * @since 4.4.003 (2008-12-09)
		 */
		protected $htmlLinkFontStyle = 'U';
		/**
		 * Counts the number of pages.
		 * @access protected
		 * @since 4.5.000 (2008-12-31)
		 */
		protected $numpages = 0;
		/**
		 * Array containing page lenghts in bytes.
		 * @access protected
		 * @since 4.5.000 (2008-12-31)
		 */
		protected $pagelen = array();
		/**
		 * Counts the number of pages.
		 * @access protected
		 * @since 4.5.000 (2008-12-31)
		 */
		protected $numimages = 0;
		/**
		 * Store the image keys.
		 * @access protected
		 * @since 4.5.000 (2008-12-31)
		 */
		protected $imagekeys = array();
		/**
		 * Lenght of the buffer in bytes.
		 * @access protected
		 * @since 4.5.000 (2008-12-31)
		 */
		protected $bufferlen = 0;
		/**
		 * If true enables disk caching.
		 * @access protected
		 * @since 4.5.000 (2008-12-31)
		 */
		protected $diskcache = false;
		/**
		 * Counts the number of fonts.
		 * @access protected
		 * @since 4.5.000 (2009-01-02)
		 */
		protected $numfonts = 0;
		/**
		 * Store the font keys.
		 * @access protected
		 * @since 4.5.000 (2009-01-02)
		 */
		protected $fontkeys = array();
		/**
		 * Store the font object IDs.
		 * @access protected
		 * @since 4.8.001 (2009-09-09)
		 */
		protected $font_obj_ids = array();
		/**
		 * Store the fage status (true when opened, false when closed).
		 * @access protected
		 * @since 4.5.000 (2009-01-02)
		 */
		protected $pageopen = array();
		/**
		 * Default monospaced font
		 * @access protected
		 * @since 4.5.025 (2009-03-10)
		 */
		protected $default_monospaced_font = 'courier';
		/**
		 * Used to store a cloned copy of the current class object
		 * @access protected
		 * @since 4.5.029 (2009-03-19)
		 */
		protected $objcopy;
		/**
		 * Array used to store the lenghts of cache files
		 * @access protected
		 * @since 4.5.029 (2009-03-19)
		 */
		protected $cache_file_lenght = array();
		/**
		 * Table header content to be repeated on each new page
		 * @access protected
		 * @since 4.5.030 (2009-03-20)
		 */
		protected $thead = '';
		/**
		 * Margins used for table header.
		 * @access protected
		 * @since 4.5.030 (2009-03-20)
		 */
		protected $theadMargins = array();
		/**
		 * Cache array for UTF8StringToArray() method.
		 * @access protected
		 * @since 4.5.037 (2009-04-07)
		 */
		protected $cache_UTF8StringToArray = array();
		/**
		 * Maximum size of cache array used for UTF8StringToArray() method.
		 * @access protected
		 * @since 4.5.037 (2009-04-07)
		 */
		protected $cache_maxsize_UTF8StringToArray = 8;
		/**
		 * Current size of cache array used for UTF8StringToArray() method.
		 * @access protected
		 * @since 4.5.037 (2009-04-07)
		 */
		protected $cache_size_UTF8StringToArray = 0;
		/**
		 * If true enables document signing
		 * @access protected
		 * @since 4.6.005 (2009-04-24)
		 */
		protected $sign = false;
		/**
		 * Signature data
		 * @access protected
		 * @since 4.6.005 (2009-04-24)
		 */
		protected $signature_data = array();
		/**
		 * Signature max lenght
		 * @access protected
		 * @since 4.6.005 (2009-04-24)
		 */
		protected $signature_max_lenght = 11742;
		/**
		 * Regular expression used to find blank characters used for word-wrapping.
		 * @access protected
		 * @since 4.6.006 (2009-04-28)
		 */
		protected $re_spaces = '/[\\s]/';
		/**
		 * Signature object ID
		 * @access protected
		 * @since 4.6.022 (2009-06-23)
		 */
		protected $sig_obj_id = 0;
		/**
		 * ByteRange placemark used during signature process.
		 * @access protected
		 * @since 4.6.028 (2009-08-25)
		 */
		protected $byterange_string = '/ByteRange[0 ********** ********** **********]';
		/**
		 * Placemark used during signature process.
		 * @access protected
		 * @since 4.6.028 (2009-08-25)
		 */
		protected $sig_annot_ref = '***SIGANNREF*** 0 R';
		/**
		 * ID of page objects
		 * @access protected
		 * @since 4.7.000 (2009-08-29)
		 */
		protected $page_obj_id = array();
		/**
		 * Start ID for embedded file objects
		 * @access protected
		 * @since 4.7.000 (2009-08-29)
		 */
		protected $embedded_start_obj_id = 100000;
		/**
		 * Start ID for annotation objects
		 * @access protected
		 * @since 4.7.000 (2009-08-29)
		 */
		protected $annots_start_obj_id = 200000;
		/**
		 * Max ID of annotation object
		 * @access protected
		 * @since 4.7.000 (2009-08-29)
		 */
		protected $annot_obj_id = 200000;
		/**
		 * Current ID of annotation object
		 * @access protected
		 * @since 4.8.003 (2009-09-15)
		 */
		protected $curr_annot_obj_id = 200000;
		/**
		 * List of form annotations IDs
		 * @access protected
		 * @since 4.8.000 (2009-09-07)
		 */
		protected $form_obj_id = array();
		protected $default_form_prop = array(
			'lineWidth'   => 1,
			'borderStyle' => 'solid',
			'fillColor'   => array(255, 255, 255),
			'strokeColor' => array(128, 128, 128)
			);
		/**
		 * Javascript objects array
		 * @access protected
		 * @since 4.8.000 (2009-09-07)
		 */
		protected $js_objects = array();
		/**
		 * Start ID for javascript objects
		 * @access protected
		 * @since 4.8.000 (2009-09-07)
		 */
		protected $js_start_obj_id = 300000;
		/**
		 * Current ID of javascript object
		 * @access protected
		 * @since 4.8.000 (2009-09-07)
		 */
		protected $js_obj_id = 300000;
		/**
		 * Current form action (used during XHTML rendering)
		 * @access protected
		 * @since 4.8.000 (2009-09-07)
		 */
		protected $form_action = '';
		/**
		 * Current form encryption type (used during XHTML rendering)
		 * @access protected
		 * @since 4.8.000 (2009-09-07)
		 */
		protected $form_enctype = 'application/x-www-form-urlencoded';
		/**
		 * Current method to submit forms.
		 * @access protected
		 * @since 4.8.000 (2009-09-07)
		 */
		protected $form_mode = 'post';
		/**
		 * Start ID for appearance streams XObjects
		 * @access protected
		 * @since 4.8.001 (2009-09-09)
		 */
		protected $apxo_start_obj_id = 400000;
		/**
		 * Current ID of appearance streams XObjects
		 * @access protected
		 * @since 4.8.001 (2009-09-09)
		 */
		protected $apxo_obj_id = 400000;
		/**
		 * List of fonts used on form fields (fontname => fontkey).
		 * @access protected
		 * @since 4.8.001 (2009-09-09)
		 */
		protected $annotation_fonts = array();
		/**
		 * List of radio buttons parent objects.
		 * @access protected
		 * @since 4.8.001 (2009-09-09)
		 */
		protected $radiobutton_groups = array();
		/**
		 * List of radio group objects IDs
		 * @access protected
		 * @since 4.8.001 (2009-09-09)
		 */
		protected $radio_groups = array();
		/**
		 * Text indentation value (used for text-indent CSS attribute) 
		 * @access protected
		 * @since 4.8.006 (2009-09-23)
		 */
		protected $textindent = 0;
		/**
		 * Store page number when startTransaction() is called.
		 * @access protected
		 * @since 4.8.006 (2009-09-23)
		 */
		protected $start_transaction_page = 0;

		public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false)
		{
			if (function_exists('mb_internal_encoding') && mb_internal_encoding()) {
				$this->internal_encoding = mb_internal_encoding();
				mb_internal_encoding('ASCII');
			}

			$this->diskcache = $diskcache ? true : false;
			$this->rtl = false;
			$this->tmprtl = false;
			$this->_dochecks();
			$this->isunicode = $unicode;
			$this->page = 0;
			$this->transfmrk[0] = array();
			$this->pagedim = array();
			$this->n = 2;
			$this->buffer = '';
			$this->pages = array();
			$this->state = 0;
			$this->fonts = array();
			$this->FontFiles = array();
			$this->diffs = array();
			$this->images = array();
			$this->links = array();
			$this->gradients = array();
			$this->InFooter = false;
			$this->lasth = 0;
			$this->FontFamily = 'helvetica';
			$this->FontStyle = '';
			$this->FontSizePt = 12;
			$this->underline = false;
			$this->linethrough = false;
			$this->DrawColor = '0 G';
			$this->FillColor = '0 g';
			$this->TextColor = '0 g';
			$this->ColorFlag = false;
			$this->encrypted = false;
			$this->last_rc4_key = '';
			$this->padding = '(¿N^NuŠAd' . "\0" . 'NVÿ?..' . "\0" . '¶Ðh>€/©þdSiz';
			$this->CoreFonts = array('courier' => 'Courier', 'courierB' => 'Courier-Bold', 'courierI' => 'Courier-Oblique', 'courierBI' => 'Courier-BoldOblique', 'helvetica' => 'Helvetica', 'helveticaB' => 'Helvetica-Bold', 'helveticaI' => 'Helvetica-Oblique', 'helveticaBI' => 'Helvetica-BoldOblique', 'times' => 'Times-Roman', 'timesB' => 'Times-Bold', 'timesI' => 'Times-Italic', 'timesBI' => 'Times-BoldItalic', 'symbol' => 'Symbol', 'zapfdingbats' => 'ZapfDingbats');
			$this->setPageUnit($unit);
			$this->setPageFormat($format, $orientation);
			$margin = 28.35 / $this->k;
			$this->SetMargins($margin, $margin);
			$this->cMargin = $margin / 10;
			$this->LineWidth = 0.57 / $this->k;
			$this->linestyleWidth = sprintf('%.2F w', $this->LineWidth * $this->k);
			$this->linestyleCap = '0 J';
			$this->linestyleJoin = '0 j';
			$this->linestyleDash = '[] 0 d';
			$this->SetAutoPageBreak(true, 2 * $margin);
			$this->SetDisplayMode('fullwidth');
			$this->SetCompression(true);
			$this->PDFVersion = '1.7';
			$this->encoding = $encoding;
			$this->HREF = array();
			$this->getFontsList();
			$this->fgcolor = array('R' => 0, 'G' => 0, 'B' => 0);
			$this->bgcolor = array('R' => 255, 'G' => 255, 'B' => 255);
			$this->extgstates = array();
			$this->sign = false;
			$this->ur = false;
			$this->ur_document = '/FullSave';
			$this->ur_annots = '/Create/Delete/Modify/Copy/Import/Export';
			$this->ur_form = '/Add/Delete/FillIn/Import/Export/SubmitStandalone/SpawnTemplate';
			$this->ur_signature = '/Modify';
			$this->jpeg_quality = 75;
			$this->utf8Bidi(array(''), '');
			$this->SetFont($this->FontFamily, $this->FontStyle, $this->FontSizePt);
			if ($this->isunicode && (@preg_match('/\\pL/u', 'a') == 1)) {
				$this->re_spaces = '/[\\s\\p{Z}]/u';
			}
			else {
				$this->re_spaces = '/[\\s]/';
			}

			$this->annot_obj_id = $this->annots_start_obj_id;
			$this->curr_annot_obj_id = $this->annots_start_obj_id;
			$this->apxo_obj_id = $this->apxo_start_obj_id;
			$this->js_obj_id = $this->js_start_obj_id;
			$this->default_form_prop = array(
	'lineWidth'   => 1,
	'borderStyle' => 'solid',
	'fillColor'   => array(255, 255, 255),
	'strokeColor' => array(128, 128, 128)
	);
		}

		public function __destruct()
		{
			if (isset($this->internal_encoding) && !empty($this->internal_encoding)) {
				mb_internal_encoding($this->internal_encoding);
			}

			$this->_destroy(true);
		}

		public function setPageUnit($unit)
		{
			switch (strtolower($unit)) {
			case 'px':
			case 'pt':
				$this->k = 1;
				break;

			case 'mm':
				$this->k = $this->dpi / 25.4;
				break;

			case 'cm':
				$this->k = $this->dpi / 2.54;
				break;

			case 'in':
				$this->k = $this->dpi;
				break;

			default:
				$this->Error('Incorrect unit: ' . $unit);
				break;
			}

			if (isset($this->CurOrientation)) {
				$this->setPageOrientation($this->CurOrientation);
			}
		}

		public function setPageFormat($format, $orientation = 'P')
		{
			if (is_string($format)) {
				switch (strtoupper($format)) {
				case '4A0':
					$format = array(4767.87, 6740.79);
					break;

				case '2A0':
					$format = array(3370.39, 4767.87);
					break;

				case 'A0':
					$format = array(2383.94, 3370.39);
					break;

				case 'A1':
					$format = array(1683.78, 2383.94);
					break;

				case 'A2':
					$format = array(1190.55, 1683.78);
					break;

				case 'A3':
					$format = array(841.89, 1190.55);
					break;

				case 'A4':
				default:
					$format = array(595.28, 841.89);
					break;

				case 'A5':
					$format = array(419.53, 595.28);
					break;

				case 'A6':
					$format = array(297.64, 419.53);
					break;

				case 'A7':
					$format = array(209.76, 297.64);
					break;

				case 'A8':
					$format = array(147.4, 209.76);
					break;

				case 'A9':
					$format = array(104.88, 147.4);
					break;

				case 'A10':
					$format = array(73.7, 104.88);
					break;

				case 'B0':
					$format = array(2834.65, 4008.19);
					break;

				case 'B1':
					$format = array(2004.09, 2834.65);
					break;

				case 'B2':
					$format = array(1417.32, 2004.09);
					break;

				case 'B3':
					$format = array(1000.63, 1417.32);
					break;

				case 'B4':
					$format = array(708.66, 1000.63);
					break;

				case 'B5':
					$format = array(498.9, 708.66);
					break;

				case 'B6':
					$format = array(354.33, 498.9);
					break;

				case 'B7':
					$format = array(249.45, 354.33);
					break;

				case 'B8':
					$format = array(175.75, 249.45);
					break;

				case 'B9':
					$format = array(124.72, 175.75);
					break;

				case 'B10':
					$format = array(87.87, 124.72);
					break;

				case 'C0':
					$format = array(2599.37, 3676.54);
					break;

				case 'C1':
					$format = array(1836.85, 2599.37);
					break;

				case 'C2':
					$format = array(1298.27, 1836.85);
					break;

				case 'C3':
					$format = array(918.43, 1298.27);
					break;

				case 'C4':
					$format = array(649.13, 918.43);
					break;

				case 'C5':
					$format = array(459.21, 649.13);
					break;

				case 'C6':
					$format = array(323.15, 459.21);
					break;

				case 'C7':
					$format = array(229.61, 323.15);
					break;

				case 'C8':
					$format = array(161.57, 229.61);
					break;

				case 'C9':
					$format = array(113.39, 161.57);
					break;

				case 'C10':
					$format = array(79.37, 113.39);
					break;

				case 'RA0':
					$format = array(2437.8, 3458.27);
					break;

				case 'RA1':
					$format = array(1729.13, 2437.8);
					break;

				case 'RA2':
					$format = array(1218.9, 1729.13);
					break;

				case 'RA3':
					$format = array(864.57, 1218.9);
					break;

				case 'RA4':
					$format = array(609.45, 864.57);
					break;

				case 'SRA0':
					$format = array(2551.18, 3628.35);
					break;

				case 'SRA1':
					$format = array(1814.17, 2551.18);
					break;

				case 'SRA2':
					$format = array(1275.59, 1814.17);
					break;

				case 'SRA3':
					$format = array(907.09, 1275.59);
					break;

				case 'SRA4':
					$format = array(637.8, 907.09);
					break;

				case 'LETTER':
					$format = array(612, 792);
					break;

				case 'LEGAL':
					$format = array(612, 1008);
					break;

				case 'EXECUTIVE':
					$format = array(521.86, 756);
					break;

				case 'FOLIO':
					$format = array(612, 936);
					break;
				}

				$this->fwPt = $format[0];
				$this->fhPt = $format[1];
			}
			else {
				$this->fwPt = $format[0] * $this->k;
				$this->fhPt = $format[1] * $this->k;
			}

			$this->setPageOrientation($orientation);
		}

		public function setPageOrientation($orientation, $autopagebreak = '', $bottommargin = '')
		{
			$orientation = strtoupper($orientation);
			if (($orientation == 'P') || ($orientation == 'PORTRAIT')) {
				$this->CurOrientation = 'P';
				$this->wPt = $this->fwPt;
				$this->hPt = $this->fhPt;
			}
			else {
				if (($orientation == 'L') || ($orientation == 'LANDSCAPE')) {
					$this->CurOrientation = 'L';
					$this->wPt = $this->fhPt;
					$this->hPt = $this->fwPt;
				}
				else {
					$this->Error('Incorrect orientation: ' . $orientation);
				}
			}

			$this->w = $this->wPt / $this->k;
			$this->h = $this->hPt / $this->k;

			if ($this->empty_string($autopagebreak)) {
				if (isset($this->AutoPageBreak)) {
					$autopagebreak = $this->AutoPageBreak;
				}
				else {
					$autopagebreak = true;
				}
			}

			if ($this->empty_string($bottommargin)) {
				if (isset($this->bMargin)) {
					$bottommargin = $this->bMargin;
				}
				else {
					$bottommargin = (2 * 28.35) / $this->k;
				}
			}

			$this->SetAutoPageBreak($autopagebreak, $bottommargin);
			$this->pagedim[$this->page] = array('w' => $this->wPt, 'h' => $this->hPt, 'wk' => $this->w, 'hk' => $this->h, 'tm' => $this->tMargin, 'bm' => $bottommargin, 'lm' => $this->lMargin, 'rm' => $this->rMargin, 'pb' => $autopagebreak, 'or' => $this->CurOrientation, 'olm' => $this->original_lMargin, 'orm' => $this->original_rMargin);
		}

		public function setSpacesRE($re = '/[\\s]/')
		{
			$this->re_spaces = $re;
		}

		public function setRTL($enable, $resetx = true)
		{
			$enable = ($enable ? true : false);
			$resetx = $resetx && ($enable != $this->rtl);
			$this->rtl = $enable;
			$this->tmprtl = false;

			if ($resetx) {
				$this->Ln(0);
			}
		}

		public function getRTL()
		{
			return $this->rtl;
		}

		public function setTempRTL($mode)
		{
			switch ($mode) {
			case false:
			case 'L':
			case 'R':
				$this->tmprtl = $mode;
			}
		}

		public function setLastH($h)
		{
			$this->lasth = $h;
		}

		public function getLastH()
		{
			return $this->lasth;
		}

		public function setImageScale($scale)
		{
			$this->imgscale = $scale;
		}

		public function getImageScale()
		{
			return $this->imgscale;
		}

		public function getPageDimensions($pagenum = '')
		{
			if (empty($pagenum)) {
				$pagenum = $this->page;
			}

			return $this->pagedim[$pagenum];
		}

		public function getPageWidth($pagenum = '')
		{
			if (empty($pagenum)) {
				return $this->w;
			}

			return $this->pagedim[$pagenum]['w'];
		}

		public function getPageHeight($pagenum = '')
		{
			if (empty($pagenum)) {
				return $this->h;
			}

			return $this->pagedim[$pagenum]['h'];
		}

		public function getBreakMargin($pagenum = '')
		{
			if (empty($pagenum)) {
				return $this->bMargin;
			}

			return $this->pagedim[$pagenum]['bm'];
		}

		public function getScaleFactor()
		{
			return $this->k;
		}

		public function SetMargins($left, $top, $right = -1)
		{
			$this->lMargin = $left;
			$this->tMargin = $top;

			if ($right == -1) {
				$right = $left;
			}

			$this->rMargin = $right;
		}

		public function SetLeftMargin($margin)
		{
			$this->lMargin = $margin;
			if ((0 < $this->page) && ($this->x < $margin)) {
				$this->x = $margin;
			}
		}

		public function SetTopMargin($margin)
		{
			$this->tMargin = $margin;
			if ((0 < $this->page) && ($this->y < $margin)) {
				$this->y = $margin;
			}
		}

		public function SetRightMargin($margin)
		{
			$this->rMargin = $margin;
			if ((0 < $this->page) && (($this->w - $margin) < $this->x)) {
				$this->x = $this->w - $margin;
			}
		}

		public function SetCellPadding($pad)
		{
			$this->cMargin = $pad;
		}

		public function SetAutoPageBreak($auto, $margin = 0)
		{
			$this->AutoPageBreak = $auto;
			$this->bMargin = $margin;
			$this->PageBreakTrigger = $this->h - $margin;
		}

		public function SetDisplayMode($zoom, $layout = 'SinglePage', $mode = 'UseNone')
		{
			if (($zoom == 'fullpage') || ($zoom == 'fullwidth') || ($zoom == 'real') || ($zoom == 'default') || !is_string($zoom)) {
				$this->ZoomMode = $zoom;
			}
			else {
				$this->Error('Incorrect zoom display mode: ' . $zoom);
			}

			switch ($layout) {
			case 'default':
			case 'single':
			case 'SinglePage':
				$this->LayoutMode = 'SinglePage';
				break;

			case 'continuous':
			case 'OneColumn':
				$this->LayoutMode = 'OneColumn';
				break;

			case 'two':
			case 'TwoColumnLeft':
				$this->LayoutMode = 'TwoColumnLeft';
				break;

			case 'TwoColumnRight':
				$this->LayoutMode = 'TwoColumnRight';
				break;

			case 'TwoPageLeft':
				$this->LayoutMode = 'TwoPageLeft';
				break;

			case 'TwoPageRight':
				$this->LayoutMode = 'TwoPageRight';
				break;

			default:
				$this->LayoutMode = 'SinglePage';
			}

			switch ($mode) {
			case 'UseNone':
				$this->PageMode = 'UseNone';
				break;

			case 'UseOutlines':
				$this->PageMode = 'UseOutlines';
				break;

			case 'UseThumbs':
				$this->PageMode = 'UseThumbs';
				break;

			case 'FullScreen':
				$this->PageMode = 'FullScreen';
				break;

			case 'UseOC':
				$this->PageMode = 'UseOC';
				break;

			case '':
				$this->PageMode = 'UseAttachments';
				break;

			default:
				$this->PageMode = 'UseNone';
			}
		}

		public function SetCompression($compress)
		{
			if (function_exists('gzcompress')) {
				$this->compress = $compress;
			}
			else {
				$this->compress = false;
			}
		}

		public function SetTitle($title)
		{
			$this->title = $title;
		}

		public function SetSubject($subject)
		{
			$this->subject = $subject;
		}

		public function SetAuthor($author)
		{
			$this->author = $author;
		}

		public function SetKeywords($keywords)
		{
			$this->keywords = $keywords;
		}

		public function SetCreator($creator)
		{
			$this->creator = $creator;
		}

		public function Error($msg)
		{
			$this->_destroy(true);
			exit('<strong>TCPDF ERROR: </strong>' . $msg);
		}

		public function Open()
		{
			$this->state = 1;
		}

		public function Close()
		{
			if ($this->state == 3) {
				return NULL;
			}

			if ($this->page == 0) {
				$this->AddPage();
			}

			$this->endPage();
			$this->_enddoc();
			$this->_destroy(false);
		}

		public function setPage($pnum, $resetmargins = false)
		{
			if ($pnum == $this->page) {
				return NULL;
			}

			if ((0 < $pnum) && ($pnum <= $this->numpages)) {
				$this->state = 2;
				$oldpage = $this->page;
				$this->page = $pnum;
				$this->wPt = $this->pagedim[$this->page]['w'];
				$this->hPt = $this->pagedim[$this->page]['h'];
				$this->w = $this->wPt / $this->k;
				$this->h = $this->hPt / $this->k;
				$this->tMargin = $this->pagedim[$this->page]['tm'];
				$this->bMargin = $this->pagedim[$this->page]['bm'];
				$this->original_lMargin = $this->pagedim[$this->page]['olm'];
				$this->original_rMargin = $this->pagedim[$this->page]['orm'];
				$this->AutoPageBreak = $this->pagedim[$this->page]['pb'];
				$this->CurOrientation = $this->pagedim[$this->page]['or'];
				$this->SetAutoPageBreak($this->AutoPageBreak, $this->bMargin);

				if ($resetmargins) {
					$this->lMargin = $this->pagedim[$this->page]['olm'];
					$this->rMargin = $this->pagedim[$this->page]['orm'];
					$this->SetY($this->tMargin);
				}
				else if ($this->pagedim[$this->page]['olm'] != $this->pagedim[$oldpage]['olm']) {
					$deltam = $this->pagedim[$this->page]['olm'] - $this->pagedim[$this->page]['orm'];
					$this->lMargin += $deltam;
					$this->rMargin -= $deltam;
				}
			}
			else {
				$this->Error('Wrong page number on setPage() function.');
			}
		}

		public function lastPage($resetmargins = false)
		{
			$this->setPage($this->getNumPages(), $resetmargins);
		}

		public function getPage()
		{
			return $this->page;
		}

		public function getNumPages()
		{
			return $this->numpages;
		}

		public function AddPage($orientation = '', $format = '')
		{
			if (!isset($this->original_lMargin)) {
				$this->original_lMargin = $this->lMargin;
			}

			if (!isset($this->original_rMargin)) {
				$this->original_rMargin = $this->rMargin;
			}

			$this->endPage();
			$this->startPage($orientation, $format);
		}

		protected function endPage()
		{
			if (($this->page == 0) || ($this->page < $this->numpages) || !$this->pageopen[$this->page]) {
				return NULL;
			}

			$this->InFooter = true;
			$this->setFooter();
			$this->_endpage();
			$this->pageopen[$this->page] = false;
			$this->InFooter = false;
		}

		protected function startPage($orientation = '', $format = '')
		{
			if ($this->page < $this->numpages) {
				$this->setPage($this->page + 1);
				$this->SetY($this->tMargin);
				return NULL;
			}

			if ($this->state == 0) {
				$this->Open();
			}

			++$this->numpages;
			$this->swapMargins($this->booklet);
			$gvars = $this->getGraphicVars();
			$this->_beginpage($orientation, $format);
			$this->pageopen[$this->page] = true;
			$this->setGraphicVars($gvars);
			$this->setPageMark();
			$this->setHeader();
			$this->setGraphicVars($gvars);
			$this->setPageMark();
			$this->setTableHeader();
		}

		public function setPageMark()
		{
			$this->intmrk[$this->page] = $this->pagelen[$this->page];
			$this->setContentMark();
		}

		protected function setContentMark($page = 0)
		{
			if ($page <= 0) {
				$page = $this->page;
			}

			if (isset($this->footerlen[$page])) {
				$this->cntmrk[$page] = $this->pagelen[$page] - $this->footerlen[$page];
			}
			else {
				$this->cntmrk[$page] = $this->pagelen[$page];
			}
		}

		public function setHeaderData($ln = '', $lw = 0, $ht = '', $hs = '')
		{
			$this->header_logo = $ln;
			$this->header_logo_width = $lw;
			$this->header_title = $ht;
			$this->header_string = $hs;
		}

		public function getHeaderData()
		{
			$ret = array();
			$ret['logo'] = $this->header_logo;
			$ret['logo_width'] = $this->header_logo_width;
			$ret['title'] = $this->header_title;
			$ret['string'] = $this->header_string;
			return $ret;
		}

		public function setHeaderMargin($hm = 10)
		{
			$this->header_margin = $hm;
		}

		public function getHeaderMargin()
		{
			return $this->header_margin;
		}

		public function setFooterMargin($fm = 10)
		{
			$this->footer_margin = $fm;
		}

		public function getFooterMargin()
		{
			return $this->footer_margin;
		}

		public function setPrintHeader($val = true)
		{
			$this->print_header = $val;
		}

		public function setPrintFooter($val = true)
		{
			$this->print_footer = $val;
		}

		public function getImageRBX()
		{
			return $this->img_rb_x;
		}

		public function getImageRBY()
		{
			return $this->img_rb_y;
		}

		public function Header()
		{
			$ormargins = $this->getOriginalMargins();
			$headerfont = $this->getHeaderFont();
			$headerdata = $this->getHeaderData();
			if ($headerdata['logo'] && ($headerdata['logo'] != K_BLANK_IMAGE)) {
				$this->Image(K_PATH_IMAGES . $headerdata['logo'], $this->GetX(), $this->getHeaderMargin(), $headerdata['logo_width']);
				$imgy = $this->getImageRBY();
			}
			else {
				$imgy = $this->GetY();
			}

			$cell_height = round(($this->getCellHeightRatio() * $headerfont[2]) / $this->getScaleFactor(), 2);

			if ($this->getRTL()) {
				$header_x = $ormargins['right'] + ($headerdata['logo_width'] * 1.1);
			}
			else {
				$header_x = $ormargins['left'] + ($headerdata['logo_width'] * 1.1);
			}

			$this->SetTextColor(0, 0, 0);
			$this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
			$this->SetX($header_x);
			$this->Cell(0, $cell_height, $headerdata['title'], 0, 1, '', 0, '', 0);
			$this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
			$this->SetX($header_x);
			$this->MultiCell(0, $cell_height, $headerdata['string'], 0, '', 0, 1, '', '', true, 0, false);
			$this->SetLineStyle(array(
	'width' => 0.85 / $this->getScaleFactor(),
	'cap'   => 'butt',
	'join'  => 'miter',
	'dash'  => 0,
	'color' => array(0, 0, 0)
	));
			$this->SetY((2.835 / $this->getScaleFactor()) + max($imgy, $this->GetY()));

			if ($this->getRTL()) {
				$this->SetX($ormargins['right']);
			}
			else {
				$this->SetX($ormargins['left']);
			}

			$this->Cell(0, 0, '', 'T', 0, 'C');
		}

		public function Footer()
		{
			$cur_y = $this->GetY();
			$ormargins = $this->getOriginalMargins();
			$this->SetTextColor(0, 0, 0);
			$line_width = 0.85 / $this->getScaleFactor();
			$this->SetLineStyle(array(
	'width' => $line_width,
	'cap'   => 'butt',
	'join'  => 'miter',
	'dash'  => 0,
	'color' => array(0, 0, 0)
	));
			$barcode = $this->getBarcode();

			if (!empty($barcode)) {
				$this->Ln($line_width);
				$barcode_width = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
				$this->write1DBarcode($barcode, 'C128B', $this->GetX(), $cur_y + $line_width, $barcode_width, ($this->getFooterMargin() / 3) - $line_width, 0.3, '', '');
			}

			if (empty($this->pagegroups)) {
				$pagenumtxt = $this->l['w_page'] . ' ' . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages();
			}
			else {
				$pagenumtxt = $this->l['w_page'] . ' ' . $this->getPageNumGroupAlias() . ' / ' . $this->getPageGroupAlias();
			}

			$this->SetY($cur_y);

			if ($this->getRTL()) {
				$this->SetX($ormargins['right']);
				$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
			}
			else {
				$this->SetX($ormargins['left']);
				$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'R');
			}
		}

		protected function setHeader()
		{
			if ($this->print_header) {
				$lasth = $this->lasth;
				$this->_out('q');
				$this->rMargin = $this->original_rMargin;
				$this->lMargin = $this->original_lMargin;
				$this->cMargin = 0;

				if ($this->rtl) {
					$this->SetXY($this->original_rMargin, $this->header_margin);
				}
				else {
					$this->SetXY($this->original_lMargin, $this->header_margin);
				}

				$this->SetFont($this->header_font[0], $this->header_font[1], $this->header_font[2]);
				$this->Header();

				if ($this->rtl) {
					$this->SetXY($this->original_rMargin, $this->tMargin);
				}
				else {
					$this->SetXY($this->original_lMargin, $this->tMargin);
				}

				$this->_out('Q');
				$this->lasth = $lasth;
			}
		}

		protected function setFooter()
		{
			$gvars = $this->getGraphicVars();
			$this->footerpos[$this->page] = $this->pagelen[$this->page];
			$this->_out("\n");

			if ($this->print_footer) {
				$lasth = $this->lasth;
				$this->_out('q');
				$this->rMargin = $this->original_rMargin;
				$this->lMargin = $this->original_lMargin;
				$this->cMargin = 0;
				$footer_y = $this->h - $this->footer_margin;

				if ($this->rtl) {
					$this->SetXY($this->original_rMargin, $footer_y);
				}
				else {
					$this->SetXY($this->original_lMargin, $footer_y);
				}

				$this->SetFont($this->footer_font[0], $this->footer_font[1], $this->footer_font[2]);
				$this->Footer();

				if ($this->rtl) {
					$this->SetXY($this->original_rMargin, $this->tMargin);
				}
				else {
					$this->SetXY($this->original_lMargin, $this->tMargin);
				}

				$this->_out('Q');
				$this->lasth = $lasth;
			}

			$this->setGraphicVars($gvars);
			$this->footerlen[$this->page] = ($this->pagelen[$this->page] - $this->footerpos[$this->page]) + 1;
		}

		protected function setTableHeader()
		{
			if (isset($this->theadMargins['top'])) {
				$this->tMargin = $this->theadMargins['top'];
				$this->pagedim[$this->page]['tm'] = $this->tMargin;
				$this->y = $this->tMargin;
			}

			if (!$this->empty_string($this->thead)) {
				$prev_lMargin = $this->lMargin;
				$prev_rMargin = $this->rMargin;
				$this->lMargin = $this->pagedim[$this->page]['olm'];
				$this->rMargin = $this->pagedim[$this->page]['orm'];
				$this->cMargin = $this->theadMargins['cmargin'];
				$this->writeHTML($this->thead, false, false, false, false, '');

				if (!isset($this->theadMargins['top'])) {
					$this->theadMargins['top'] = $this->tMargin;
				}

				$this->tMargin = $this->y;
				$this->pagedim[$this->page]['tm'] = $this->tMargin;
				$this->lasth = 0;
				$this->lMargin = $prev_lMargin;
				$this->rMargin = $prev_rMargin;
			}
		}

		public function PageNo()
		{
			return $this->page;
		}

		public function AddSpotColor($name, $c, $m, $y, $k)
		{
			if (!isset($this->spot_colors[$name])) {
				$i = 1 + count($this->spot_colors);
				$this->spot_colors[$name] = array('i' => $i, 'c' => $c, 'm' => $m, 'y' => $y, 'k' => $k);
			}
		}

		public function SetDrawColorArray($color)
		{
			if (isset($color)) {
				$color = array_values($color);
				$r = (isset($color[0]) ? $color[0] : -1);
				$g = (isset($color[1]) ? $color[1] : -1);
				$b = (isset($color[2]) ? $color[2] : -1);
				$k = (isset($color[3]) ? $color[3] : -1);

				if (0 <= $r) {
					$this->SetDrawColor($r, $g, $b, $k);
				}
			}
		}

		public function SetDrawColor($col1 = 0, $col2 = -1, $col3 = -1, $col4 = -1)
		{
			if (!is_numeric($col1)) {
				$col1 = 0;
			}

			if (!is_numeric($col2)) {
				$col2 = -1;
			}

			if (!is_numeric($col3)) {
				$col3 = -1;
			}

			if (!is_numeric($col4)) {
				$col4 = -1;
			}

			if (($col2 == -1) && ($col3 == -1) && ($col4 == -1)) {
				$this->DrawColor = sprintf('%.3F G', $col1 / 255);
			}
			else if ($col4 == -1) {
				$this->DrawColor = sprintf('%.3F %.3F %.3F RG', $col1 / 255, $col2 / 255, $col3 / 255);
			}
			else {
				$this->DrawColor = sprintf('%.3F %.3F %.3F %.3F K', $col1 / 100, $col2 / 100, $col3 / 100, $col4 / 100);
			}

			if (0 < $this->page) {
				$this->_out($this->DrawColor);
			}
		}

		public function SetDrawSpotColor($name, $tint = 100)
		{
			if (!isset($this->spot_colors[$name])) {
				$this->Error('Undefined spot color: ' . $name);
			}

			$this->DrawColor = sprintf('/CS%d CS %.3F SCN', $this->spot_colors[$name]['i'], $tint / 100);

			if (0 < $this->page) {
				$this->_out($this->DrawColor);
			}
		}

		public function SetFillColorArray($color)
		{
			if (isset($color)) {
				$color = array_values($color);
				$r = (isset($color[0]) ? $color[0] : -1);
				$g = (isset($color[1]) ? $color[1] : -1);
				$b = (isset($color[2]) ? $color[2] : -1);
				$k = (isset($color[3]) ? $color[3] : -1);

				if (0 <= $r) {
					$this->SetFillColor($r, $g, $b, $k);
				}
			}
		}

		public function SetFillColor($col1 = 0, $col2 = -1, $col3 = -1, $col4 = -1)
		{
			if (!is_numeric($col1)) {
				$col1 = 0;
			}

			if (!is_numeric($col2)) {
				$col2 = -1;
			}

			if (!is_numeric($col3)) {
				$col3 = -1;
			}

			if (!is_numeric($col4)) {
				$col4 = -1;
			}

			if (($col2 == -1) && ($col3 == -1) && ($col4 == -1)) {
				$this->FillColor = sprintf('%.3F g', $col1 / 255);
				$this->bgcolor = array('G' => $col1);
			}
			else if ($col4 == -1) {
				$this->FillColor = sprintf('%.3F %.3F %.3F rg', $col1 / 255, $col2 / 255, $col3 / 255);
				$this->bgcolor = array('R' => $col1, 'G' => $col2, 'B' => $col3);
			}
			else {
				$this->FillColor = sprintf('%.3F %.3F %.3F %.3F k', $col1 / 100, $col2 / 100, $col3 / 100, $col4 / 100);
				$this->bgcolor = array('C' => $col1, 'M' => $col2, 'Y' => $col3, 'K' => $col4);
			}

			$this->ColorFlag = $this->FillColor != $this->TextColor;

			if (0 < $this->page) {
				$this->_out($this->FillColor);
			}
		}

		public function SetFillSpotColor($name, $tint = 100)
		{
			if (!isset($this->spot_colors[$name])) {
				$this->Error('Undefined spot color: ' . $name);
			}

			$this->FillColor = sprintf('/CS%d cs %.3F scn', $this->spot_colors[$name]['i'], $tint / 100);
			$this->ColorFlag = $this->FillColor != $this->TextColor;

			if (0 < $this->page) {
				$this->_out($this->FillColor);
			}
		}

		public function SetTextColorArray($color)
		{
			if (isset($color)) {
				$color = array_values($color);
				$r = (isset($color[0]) ? $color[0] : -1);
				$g = (isset($color[1]) ? $color[1] : -1);
				$b = (isset($color[2]) ? $color[2] : -1);
				$k = (isset($color[3]) ? $color[3] : -1);

				if (0 <= $r) {
					$this->SetTextColor($r, $g, $b, $k);
				}
			}
		}

		public function SetTextColor($col1 = 0, $col2 = -1, $col3 = -1, $col4 = -1)
		{
			if (!is_numeric($col1)) {
				$col1 = 0;
			}

			if (!is_numeric($col2)) {
				$col2 = -1;
			}

			if (!is_numeric($col3)) {
				$col3 = -1;
			}

			if (!is_numeric($col4)) {
				$col4 = -1;
			}

			if (($col2 == -1) && ($col3 == -1) && ($col4 == -1)) {
				$this->TextColor = sprintf('%.3F g', $col1 / 255);
				$this->fgcolor = array('G' => $col1);
			}
			else if ($col4 == -1) {
				$this->TextColor = sprintf('%.3F %.3F %.3F rg', $col1 / 255, $col2 / 255, $col3 / 255);
				$this->fgcolor = array('R' => $col1, 'G' => $col2, 'B' => $col3);
			}
			else {
				$this->TextColor = sprintf('%.3F %.3F %.3F %.3F k', $col1 / 100, $col2 / 100, $col3 / 100, $col4 / 100);
				$this->fgcolor = array('C' => $col1, 'M' => $col2, 'Y' => $col3, 'K' => $col4);
			}

			$this->ColorFlag = $this->FillColor != $this->TextColor;
		}

		public function SetTextSpotColor($name, $tint = 100)
		{
			if (!isset($this->spot_colors[$name])) {
				$this->Error('Undefined spot color: ' . $name);
			}

			$this->TextColor = sprintf('/CS%d cs %.3F scn', $this->spot_colors[$name]['i'], $tint / 100);
			$this->ColorFlag = $this->FillColor != $this->TextColor;

			if (0 < $this->page) {
				$this->_out($this->TextColor);
			}
		}

		public function GetStringWidth($s, $fontname = '', $fontstyle = '', $fontsize = 0)
		{
			return $this->GetArrStringWidth($this->utf8Bidi($this->UTF8StringToArray($s), $s, $this->tmprtl), $fontname, $fontstyle, $fontsize);
		}

		public function GetArrStringWidth($sa, $fontname = '', $fontstyle = '', $fontsize = 0)
		{
			if (!$this->empty_string($fontname)) {
				$prev_FontFamily = $this->FontFamily;
				$prev_FontStyle = $this->FontStyle;
				$prev_FontSizePt = $this->FontSizePt;
				$this->SetFont($fontname, $fontstyle, $fontsize);
			}

			$w = 0;

			foreach ($sa as $char) {
				$w += $this->GetCharWidth($char);
			}

			if (!$this->empty_string($fontname)) {
				$this->SetFont($prev_FontFamily, $prev_FontStyle, $prev_FontSizePt);
			}

			return $w;
		}

		public function GetCharWidth($char)
		{
			if ($char == 173) {
				return 0;
			}

			$cw = &$this->CurrentFont['cw'];

			if (isset($cw[$char])) {
				$w = $cw[$char];
			}
			else if (isset($this->CurrentFont['dw'])) {
				$w = $this->CurrentFont['dw'];
			}
			else if (isset($cw[32])) {
				$dw = $cw[32];
			}
			else {
				$w = 600;
			}

			return ($w * $this->FontSize) / 1000;
		}

		public function GetNumChars($s)
		{
			if (($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) {
				return count($this->UTF8StringToArray($s));
			}

			return strlen($s);
		}

		protected function getFontsList()
		{
			$fontsdir = opendir($this->_getfontpath());

			while (($file = readdir($fontsdir)) !== false) {
				if (substr($file, -4) == '.php') {
					array_push($this->fontlist, strtolower(basename($file, '.php')));
				}
			}

			closedir($fontsdir);
		}

		public function AddFont($family, $style = '', $fontfile = '')
		{
			if ($this->empty_string($family)) {
				if (!$this->empty_string($this->FontFamily)) {
					$family = $this->FontFamily;
				}
				else {
					$this->Error('Empty font family');
				}
			}

			$family = strtolower($family);
			if (!$this->isunicode && ($family == 'arial')) {
				$family = 'helvetica';
			}

			if (($family == 'symbol') || ($family == 'zapfdingbats')) {
				$style = '';
			}

			$tempstyle = strtoupper($style);
			$style = '';

			if (strpos($tempstyle, 'U') !== false) {
				$this->underline = true;
			}
			else {
				$this->underline = false;
			}

			if (strpos($tempstyle, 'D') !== false) {
				$this->linethrough = true;
			}
			else {
				$this->linethrough = false;
			}

			if (strpos($tempstyle, 'B') !== false) {
				$style .= 'B';
			}

			if (strpos($tempstyle, 'I') !== false) {
				$style .= 'I';
			}

			$bistyle = $style;
			$fontkey = $family . $style;
			$font_style = $style . ($this->underline ? 'U' : '') . ($this->linethrough ? 'D' : '');
			$fontdata = array('fontkey' => $fontkey, 'family' => $family, 'style' => $font_style);

			if ($this->getFontBuffer($fontkey) !== false) {
				return $fontdata;
			}

			if (isset($type)) {
				unset($type);
			}

			if (isset($cw)) {
				unset($cw);
			}

			$fontdir = '';

			if (!$this->empty_string($fontfile)) {
				$fontdir = dirname($fontfile);
				if ($this->empty_string($fontdir) || ($fontdir == '.')) {
					$fontdir = '';
				}
				else {
					$fontdir .= '/';
				}
			}

			if ($this->empty_string($fontfile) || !file_exists($fontfile)) {
				$fontfile1 = str_replace(' ', '', $family) . strtolower($style) . '.php';
				$fontfile2 = str_replace(' ', '', $family) . '.php';

				if (file_exists($fontdir . $fontfile1)) {
					$fontfile = $fontdir . $fontfile1;
				}
				else if (file_exists($this->_getfontpath() . $fontfile1)) {
					$fontfile = $this->_getfontpath() . $fontfile1;
				}
				else if (file_exists($fontfile1)) {
					$fontfile = $fontfile1;
				}
				else if (file_exists($fontdir . $fontfile2)) {
					$fontfile = $fontdir . $fontfile2;
				}
				else if (file_exists($this->_getfontpath() . $fontfile2)) {
					$fontfile = $this->_getfontpath() . $fontfile2;
				}
				else {
					$fontfile = $fontfile2;
				}
			}

			if (file_exists($fontfile)) {
				include $fontfile;
			}
			else {
				$this->Error('Could not include font definition file: ' . $family . '');
			}

			if (!isset($type) || !isset($cw)) {
				$this->Error('The font definition file has a bad format: ' . $fontfile . '');
			}

			if (!isset($file) || $this->empty_string($file)) {
				$file = '';
			}

			if (!isset($enc) || $this->empty_string($enc)) {
				$enc = '';
			}

			if (!isset($cidinfo) || $this->empty_string($cidinfo)) {
				$cidinfo = array('Registry' => 'Adobe', 'Ordering' => 'Identity', 'Supplement' => 0);
				$cidinfo['uni2cid'] = array();
			}

			if (!isset($ctg) || $this->empty_string($ctg)) {
				$ctg = '';
			}

			if (!isset($desc) || $this->empty_string($desc)) {
				$desc = array();
			}

			if (!isset($up) || $this->empty_string($up)) {
				$up = -100;
			}

			if (!isset($ut) || $this->empty_string($ut)) {
				$ut = 50;
			}

			if (!isset($cw) || $this->empty_string($cw)) {
				$cw = array();
			}

			if (!isset($dw) || $this->empty_string($dw)) {
				if (isset($desc['MissingWidth']) && (0 < $desc['MissingWidth'])) {
					$dw = $desc['MissingWidth'];
				}
				else if (isset($cw[32])) {
					$dw = $cw[32];
				}
				else {
					$dw = 600;
				}
			}

			++$this->numfonts;

			if ($type == 'cidfont0') {
				$styles = array('' => '', 'B' => ',Bold', 'I' => ',Italic', 'BI' => ',BoldItalic');
				$sname = $name . $styles[$bistyle];
				if ((strpos($bistyle, 'B') !== false) && isset($desc['StemV']) && ($desc['StemV'] == 70)) {
					$desc['StemV'] = 120;
				}
			}
			else if ($type == 'core') {
				$name = $this->CoreFonts[$fontkey];
			}
			else {
				if (($type == 'TrueType') || ($type == 'Type1')) {
				}
				else if ($type == 'TrueTypeUnicode') {
					$enc = 'Identity-H';
				}
				else {
					$this->Error('Unknow font type: ' . $type . '');
				}
			}

			$this->setFontBuffer($fontkey, array('i' => $this->numfonts, 'type' => $type, 'name' => $name, 'desc' => $desc, 'up' => $up, 'ut' => $ut, 'cw' => $cw, 'dw' => $dw, 'enc' => $enc, 'cidinfo' => $cidinfo, 'file' => $file, 'ctg' => $ctg));
			if (isset($diff) && !empty($diff)) {
				$d = 0;
				$nb = count($this->diffs);

				for ($i = 1; $i <= $nb; ++$i) {
					if ($this->diffs[$i] == $diff) {
						$d = $i;
						break;
					}
				}

				if ($d == 0) {
					$d = $nb + 1;
					$this->diffs[$d] = $diff;
				}

				$this->setFontSubBuffer($fontkey, 'diff', $d);
			}

			if (!$this->empty_string($file)) {
				if ((strcasecmp($type, 'TrueType') == 0) || (strcasecmp($type, 'TrueTypeUnicode') == 0)) {
					$this->FontFiles[$file] = array('length1' => $originalsize, 'fontdir' => $fontdir);
				}
				else if ($type != 'core') {
					$this->FontFiles[$file] = array('length1' => $size1, 'length2' => $size2, 'fontdir' => $fontdir);
				}
			}

			return $fontdata;
		}

		public function SetFont($family, $style = '', $size = 0, $fontfile = '')
		{
			if ($size == 0) {
				$size = $this->FontSizePt;
			}

			$fontdata = $this->AddFont($family, $style, $fontfile);
			$this->FontFamily = $fontdata['family'];
			$this->FontStyle = $fontdata['style'];
			$this->CurrentFont = $this->getFontBuffer($fontdata['fontkey']);
			$this->SetFontSize($size);
		}

		public function SetFontSize($size)
		{
			$this->FontSizePt = $size;
			$this->FontSize = $size / $this->k;
			if (isset($this->CurrentFont['desc']['Ascent']) && (0 < $this->CurrentFont['desc']['Ascent'])) {
				$this->FontAscent = ($this->CurrentFont['desc']['Ascent'] * $this->FontSize) / 1000;
			}
			else {
				$this->FontAscent = 0.8 * $this->FontSize;
			}

			if (isset($this->CurrentFont['desc']['Descent']) && (0 < $this->CurrentFont['desc']['Descent'])) {
				$this->FontDescent = ((0 - $this->CurrentFont['desc']['Descent']) * $this->FontSize) / 1000;
			}
			else {
				$this->FontDescent = 0.2 * $this->FontSize;
			}

			if ((0 < $this->page) && isset($this->CurrentFont['i'])) {
				$this->_out(sprintf('BT /F%d %.2F Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
			}
		}

		public function SetDefaultMonospacedFont($font)
		{
			$this->default_monospaced_font = $font;
		}

		public function AddLink()
		{
			$n = count($this->links) + 1;
			$this->links[$n] = array(0, 0);
			return $n;
		}

		public function SetLink($link, $y = 0, $page = -1)
		{
			if ($y == -1) {
				$y = $this->y;
			}

			if ($page == -1) {
				$page = $this->page;
			}

			$this->links[$link] = array($page, $y);
		}

		public function Link($x, $y, $w, $h, $link, $spaces = 0)
		{
			$this->Annotation($x, $y, $w, $h, $link, array('Subtype' => 'Link'), $spaces);
		}

		public function Annotation($x = '', $y = '', $w, $h, $text, $opt = array('Subtype' => 'Text'), $spaces = 0)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if (isset($this->transfmatrix)) {
				for ($i = $this->transfmatrix_key; 0 < $i; --$i) {
					$maxid = count($this->transfmatrix[$i]) - 1;

					for ($j = $maxid; 0 <= $j; --$j) {
						$ctm = $this->transfmatrix[$i][$j];

						if (isset($ctm['a'])) {
							$x = $x * $this->k;
							$y = ($this->h - $y) * $this->k;
							$w = $w * $this->k;
							$h = $h * $this->k;
							$xt = $x;
							$yt = $y;
							$x1 = ($ctm['a'] * $xt) + ($ctm['c'] * $yt) + $ctm['e'];
							$y1 = ($ctm['b'] * $xt) + ($ctm['d'] * $yt) + $ctm['f'];
							$xt = $x + $w;
							$yt = $y;
							$x2 = ($ctm['a'] * $xt) + ($ctm['c'] * $yt) + $ctm['e'];
							$y2 = ($ctm['b'] * $xt) + ($ctm['d'] * $yt) + $ctm['f'];
							$xt = $x;
							$yt = $y - $h;
							$x3 = ($ctm['a'] * $xt) + ($ctm['c'] * $yt) + $ctm['e'];
							$y3 = ($ctm['b'] * $xt) + ($ctm['d'] * $yt) + $ctm['f'];
							$xt = $x + $w;
							$yt = $y - $h;
							$x4 = ($ctm['a'] * $xt) + ($ctm['c'] * $yt) + $ctm['e'];
							$y4 = ($ctm['b'] * $xt) + ($ctm['d'] * $yt) + $ctm['f'];
							$x = min($x1, $x2, $x3, $x4);
							$y = max($y1, $y2, $y3, $y4);
							$w = (max($x1, $x2, $x3, $x4) - $x) / $this->k;
							$h = ($y - min($y1, $y2, $y3, $y4)) / $this->k;
							$x = $x / $this->k;
							$y = $this->h - ($y / $this->k);
						}
					}
				}
			}

			if ($this->page <= 0) {
				$page = 1;
			}
			else {
				$page = $this->page;
			}

			if (!isset($this->PageAnnots[$page])) {
				$this->PageAnnots[$page] = array();
			}

			$this->PageAnnots[$page][] = array('x' => $x, 'y' => $y, 'w' => $w, 'h' => $h, 'txt' => $text, 'opt' => $opt, 'numspaces' => $spaces);
			if (($opt['Subtype'] == 'FileAttachment') && !$this->empty_string($opt['FS']) && file_exists($opt['FS']) && !isset($this->embeddedfiles[basename($opt['FS'])])) {
				$this->embeddedfiles[basename($opt['FS'])] = array('file' => $opt['FS'], 'n' => count($this->embeddedfiles) + $this->embedded_start_obj_id);
			}

			if (isset($opt['mk']['i']) && file_exists($opt['mk']['i'])) {
				$this->Image($opt['mk']['i'], '', '', 10, 10, '', '', '', false, 300, '', false, false, 0, false, true);
			}

			if (isset($opt['mk']['ri']) && file_exists($opt['mk']['ri'])) {
				$this->Image($opt['mk']['ri'], '', '', 0, 0, '', '', '', false, 300, '', false, false, 0, false, true);
			}

			if (isset($opt['mk']['ix']) && file_exists($opt['mk']['ix'])) {
				$this->Image($opt['mk']['ix'], '', '', 0, 0, '', '', '', false, 300, '', false, false, 0, false, true);
			}

			++$this->annot_obj_id;
		}

		protected function _putEmbeddedFiles()
		{
			reset($this->embeddedfiles);

			foreach ($this->embeddedfiles as $filename => $filedata) {
				$data = file_get_contents($filedata['file']);
				$filter = '';

				if ($this->compress) {
					$data = gzcompress($data);
					$filter = ' /Filter /FlateDecode';
				}

				$this->offsets[$filedata['n']] = $this->bufferlen;
				$this->_out($filedata['n'] . ' 0 obj');
				$this->_out('<</Type /EmbeddedFile' . $filter . ' /Length ' . strlen($data) . ' >>');
				$this->_putstream($data);
				$this->_out('endobj');
			}
		}

		public function Text($x, $y, $txt, $stroke = 0, $clip = false)
		{
			if ($this->rtl) {
				$s = $this->utf8Bidi($this->UTF8StringToArray($txt), $txt, $this->tmprtl);
				$l = $this->GetArrStringWidth($s);
				$xr = $this->w - $x - $l;
			}
			else {
				$xr = $x;
			}

			$opt = '';
			if ((0 < $stroke) && !$clip) {
				$opt .= '1 Tr ' . intval($stroke) . ' w ';
			}
			else {
				if ((0 < $stroke) && $clip) {
					$opt .= '5 Tr ' . intval($stroke) . ' w ';
				}
				else if ($clip) {
					$opt .= '7 Tr ';
				}
			}

			$s = sprintf('BT %.2F %.2F Td %s(%s) Tj ET 0 Tr', $xr * $this->k, ($this->h - $y) * $this->k, $opt, $this->_escapetext($txt));
			if ($this->underline && ($txt != '')) {
				$s .= ' ' . $this->_dounderline($xr, $y, $txt);
			}

			if ($this->linethrough && ($txt != '')) {
				$s .= ' ' . $this->_dolinethrough($xr, $y, $txt);
			}

			if ($this->ColorFlag && !$clip) {
				$s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
			}

			$this->_out($s);
		}

		public function AcceptPageBreak()
		{
			return $this->AutoPageBreak;
		}

		protected function checkPageBreak($h = 0, $y = '', $addpage = true)
		{
			if ($this->empty_string($y)) {
				$y = $this->y;
			}

			if (($this->PageBreakTrigger < ($y + $h)) && !$this->InFooter && $this->AcceptPageBreak()) {
				if ($addpage) {
					$x = $this->x;
					$this->AddPage($this->CurOrientation);
					$this->y = $this->tMargin;
					$oldpage = $this->page - 1;

					if ($this->rtl) {
						if ($this->pagedim[$this->page]['orm'] != $this->pagedim[$oldpage]['orm']) {
							$this->x = $x - $this->pagedim[$this->page]['orm'] - $this->pagedim[$oldpage]['orm'];
						}
						else {
							$this->x = $x;
						}
					}
					else if ($this->pagedim[$this->page]['olm'] != $this->pagedim[$oldpage]['olm']) {
						$this->x = $x + ($this->pagedim[$this->page]['olm'] - $this->pagedim[$oldpage]['olm']);
					}
					else {
						$this->x = $x;
					}
				}

				return true;
			}

			return false;
		}

		public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '', $stretch = 0, $ignore_min_height = false)
		{
			$min_cell_height = $this->FontSize * $this->cell_height_ratio;

			if ($h < $min_cell_height) {
				$h = $min_cell_height;
			}

			$this->checkPageBreak($h);
			$this->_out($this->getCellCode($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height));
		}

		public function removeSHY($txt = '')
		{
			$txt = preg_replace('/([\\xc2]{1}[\\xad]{1})/', '', $txt);

			if (!$this->isunicode) {
				$txt = preg_replace('/([\\xad]{1})/', '', $txt);
			}

			return $txt;
		}

		protected function getCellCode($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '', $stretch = 0, $ignore_min_height = false)
		{
			$txt = $this->removeSHY($txt);
			$rs = '';

			if (!$ignore_min_height) {
				$min_cell_height = $this->FontSize * $this->cell_height_ratio;

				if ($h < $min_cell_height) {
					$h = $min_cell_height;
				}
			}

			$k = $this->k;
			if ($this->empty_string($w) || ($w <= 0)) {
				if ($this->rtl) {
					$w = $this->x - $this->lMargin;
				}
				else {
					$w = $this->w - $this->rMargin - $this->x;
				}
			}

			$s = '';
			if (($fill == 1) || ($border == 1)) {
				if ($fill == 1) {
					$op = ($border == 1 ? 'B' : 'f');
				}
				else {
					$op = 'S';
				}

				if ($this->rtl) {
					$xk = ($this->x - $w) * $k;
				}
				else {
					$xk = $this->x * $k;
				}

				$s .= sprintf('%.2F %.2F %.2F %.2F re %s ', $xk, ($this->h - $this->y) * $k, $w * $k, (0 - $h) * $k, $op);
			}

			if (is_string($border)) {
				$lm = $this->LineWidth / 2;
				$x = $this->x;
				$y = $this->y;

				if (strpos($border, 'L') !== false) {
					if ($this->rtl) {
						$xk = ($x - $w) * $k;
					}
					else {
						$xk = $x * $k;
					}

					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $xk, (($this->h - $y) + $lm) * $k, $xk, ($this->h - ($y + $h + $lm)) * $k);
				}

				if (strpos($border, 'T') !== false) {
					if ($this->rtl) {
						$xk = (($x - $w) + $lm) * $k;
						$xwk = ($x - $lm) * $k;
					}
					else {
						$xk = ($x - $lm) * $k;
						$xwk = ($x + $w + $lm) * $k;
					}

					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $xk, ($this->h - $y) * $k, $xwk, ($this->h - $y) * $k);
				}

				if (strpos($border, 'R') !== false) {
					if ($this->rtl) {
						$xk = $x * $k;
					}
					else {
						$xk = ($x + $w) * $k;
					}

					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $xk, (($this->h - $y) + $lm) * $k, $xk, ($this->h - ($y + $h + $lm)) * $k);
				}

				if (strpos($border, 'B') !== false) {
					if ($this->rtl) {
						$xk = (($x - $w) + $lm) * $k;
						$xwk = ($x - $lm) * $k;
					}
					else {
						$xk = ($x - $lm) * $k;
						$xwk = ($x + $w + $lm) * $k;
					}

					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $xk, ($this->h - ($y + $h)) * $k, $xwk, ($this->h - ($y + $h)) * $k);
				}
			}

			if ($txt != '') {
				$width = $this->GetStringWidth($txt);

				if ($width <= 0) {
					$ratio = 1;
				}
				else {
					$ratio = ($w - (2 * $this->cMargin)) / $width;
				}

				if ((0 < $stretch) && (($ratio < 1) || ((1 < $ratio) && (($stretch % 2) == 0)))) {
					if (2 < $stretch) {
						$char_space = (($w - $width - (2 * $this->cMargin)) * $this->k) / max($this->GetNumChars($txt) - 1, 1);
						$rs .= sprintf('BT %.2F Tc ET ', $char_space);
					}
					else {
						$horiz_scale = $ratio * 100;
						$rs .= sprintf('BT %.2F Tz ET ', $horiz_scale);
					}

					$align = '';
					$width = $w - (2 * $this->cMargin);
				}
				else {
					$stretch == 0;
				}

				if ($align == 'L') {
					if ($this->rtl) {
						$dx = $w - $width - $this->cMargin;
					}
					else {
						$dx = $this->cMargin;
					}
				}
				else if ($align == 'R') {
					if ($this->rtl) {
						$dx = $this->cMargin;
					}
					else {
						$dx = $w - $width - $this->cMargin;
					}
				}
				else if ($align == 'C') {
					$dx = ($w - $width) / 2;
				}
				else if ($align == 'J') {
					if ($this->rtl) {
						$dx = $w - $width - $this->cMargin;
					}
					else {
						$dx = $this->cMargin;
					}
				}
				else {
					$dx = $this->cMargin;
				}

				if ($this->ColorFlag) {
					$s .= 'q ' . $this->TextColor . ' ';
				}

				$txt2 = $this->_escapetext($txt);

				if ($this->rtl) {
					$xdk = ($this->x - $dx - $width) * $k;
				}
				else {
					$xdk = ($this->x + $dx) * $k;
				}

				if ($align == 'J') {
					$ns = substr_count($txt, ' ');
					if (($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) {
						$width = $this->GetStringWidth(str_replace(' ', '', $txt));
						$spacewidth = (-1000 * ($w - $width - (2 * $this->cMargin))) / ($ns ? $ns : 1) / $this->FontSize;
						$txt2 = str_replace(chr(0) . ' ', ') ' . $spacewidth . ' (', $txt2);
					}
					else {
						$width = $this->GetStringWidth($txt);
						$spacewidth = (($w - $width - (2 * $this->cMargin)) / ($ns ? $ns : 1)) * $this->k;
						$rs .= sprintf('BT %.3F Tw ET ', $spacewidth);
					}
				}

				$basefonty = $this->y + ($h / 2) + ($this->FontSize / 3);
				$s .= sprintf('BT %.2F %.2F Td [(%s)] TJ ET', $xdk, ($this->h - $basefonty) * $k, $txt2);

				if ($this->rtl) {
					$xdx = $this->x - $dx - $width;
				}
				else {
					$xdx = $this->x + $dx;
				}

				if ($this->underline) {
					$s .= ' ' . $this->_dounderlinew($xdx, $basefonty, $width);
				}

				if ($this->linethrough) {
					$s .= ' ' . $this->_dolinethroughw($xdx, $basefonty, $width);
				}

				if ($this->ColorFlag) {
					$s .= ' Q';
				}

				if ($link) {
					$this->Link($xdx, $this->y + (($h - $this->FontSize) / 2), $width, $this->FontSize, $link, substr_count($txt, chr(32)));
				}
			}

			if ($s) {
				$rs .= $s;

				if (2 < $stretch) {
					$rs .= ' BT 0 Tc ET';
				}
				else if (0 < $stretch) {
					$rs .= ' BT 100 Tz ET';
				}
			}

			if (!(($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) && ($align == 'J')) {
				$rs .= ' BT 0 Tw ET';
			}

			$this->lasth = $h;

			if (0 < $ln) {
				$this->y += $h;

				if ($ln == 1) {
					if ($this->rtl) {
						$this->x = $this->w - $this->rMargin;
					}
					else {
						$this->x = $this->lMargin;
					}
				}
			}
			else if ($this->rtl) {
				$this->x -= $w;
			}
			else {
				$this->x += $w;
			}

			$gstyles = '' . $this->linestyleWidth . ' ' . $this->linestyleCap . ' ' . $this->linestyleJoin . ' ' . $this->linestyleDash . ' ' . $this->DrawColor . ' ' . $this->FillColor . "\n";
			$rs = $gstyles . $rs;
			return $rs;
		}

		public function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = 0, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0)
		{
			if ($this->empty_string($this->lasth) || $reseth) {
				$this->lasth = $this->FontSize * $this->cell_height_ratio;
			}

			if (!$this->empty_string($y)) {
				$this->SetY($y);
			}
			else {
				$y = $this->GetY();
			}

			$this->checkPageBreak($h);
			$y = $this->GetY();
			$startpage = $this->page;

			if (!$this->empty_string($x)) {
				$this->SetX($x);
			}
			else {
				$x = $this->GetX();
			}

			if ($this->empty_string($w) || ($w <= 0)) {
				if ($this->rtl) {
					$w = $this->x - $this->lMargin;
				}
				else {
					$w = $this->w - $this->rMargin - $this->x;
				}
			}

			$lMargin = $this->lMargin;
			$rMargin = $this->rMargin;

			if ($this->rtl) {
				$this->SetRightMargin($this->w - $this->x);
				$this->SetLeftMargin($this->x - $w);
			}
			else {
				$this->SetLeftMargin($this->x);
				$this->SetRightMargin($this->w - $this->x - $w);
			}

			$starty = $this->y;

			if ($autopadding) {
				if ($this->cMargin < ($this->LineWidth / 2)) {
					$this->cMargin = $this->LineWidth / 2;
				}

				if (($this->lasth - $this->FontSize) < $this->LineWidth) {
					$this->y += $this->LineWidth / 2;
				}

				$this->y += $this->cMargin;
			}

			if ($ishtml) {
				$this->writeHTML($txt, true, 0, $reseth, true, $align);
				$nl = 1;
			}
			else {
				$nl = $this->Write($this->lasth, $txt, '', 0, $align, true, $stretch, false, false, $maxh);
			}

			if ($autopadding) {
				$this->y += $this->cMargin;

				if (($this->lasth - $this->FontSize) < $this->LineWidth) {
					$this->y += $this->LineWidth / 2;
				}
			}

			$currentY = $this->y;
			$endpage = $this->page;

			if ($startpage < $endpage) {
				for ($page = $startpage; $page <= $endpage; ++$page) {
					$this->setPage($page);

					if ($page == $startpage) {
						$this->y = $starty;
						$h = $this->getPageHeight() - $starty - $this->getBreakMargin();
						$cborder = $this->getBorderMode($border, $position = 'start');
					}
					else if ($page == $endpage) {
						$this->y = $this->tMargin;
						$h = $currentY - $this->tMargin;
						$cborder = $this->getBorderMode($border, $position = 'end');
					}
					else {
						$this->y = $this->tMargin;
						$h = $this->getPageHeight() - $this->tMargin - $this->getBreakMargin();
						$cborder = $this->getBorderMode($border, $position = 'middle');
					}

					$nx = $x;

					if ($startpage < $page) {
						if ($this->rtl && ($this->pagedim[$page]['orm'] != $this->pagedim[$startpage]['orm'])) {
							$nx = $x + ($this->pagedim[$page]['orm'] - $this->pagedim[$startpage]['orm']);
						}
						else {
							if (!$this->rtl && ($this->pagedim[$page]['olm'] != $this->pagedim[$startpage]['olm'])) {
								$nx = $x + ($this->pagedim[$page]['olm'] - $this->pagedim[$startpage]['olm']);
							}
						}
					}

					$this->SetX($nx);
					$ccode = $this->getCellCode($w, $h, '', $cborder, 1, '', $fill, '', 0, false);
					if ($cborder || $fill) {
						$pagebuff = $this->getPageBuffer($this->page);
						$pstart = substr($pagebuff, 0, $this->intmrk[$this->page]);
						$pend = substr($pagebuff, $this->intmrk[$this->page]);
						$this->setPageBuffer($this->page, $pstart . $ccode . "\n" . $pend);
						$this->intmrk[$this->page] += strlen($ccode . "\n");
					}
				}
			}
			else {
				$h = max($h, $currentY - $y);
				$this->SetY($y);
				$this->SetX($x);
				$ccode = $this->getCellCode($w, $h, '', $border, 1, '', $fill, '', 0, true);
				if ($border || $fill) {
					if (end($this->transfmrk[$this->page]) !== false) {
						$pagemarkkey = key($this->transfmrk[$this->page]);
						$pagemark = &$this->transfmrk[$this->page][$pagemarkkey];
					}
					else if ($this->InFooter) {
						$pagemark = &$this->footerpos[$this->page];
					}
					else {
						$pagemark = &$this->intmrk[$this->page];
					}

					$pagebuff = $this->getPageBuffer($this->page);
					$pstart = substr($pagebuff, 0, $pagemark);
					$pend = substr($pagebuff, $pagemark);
					$this->setPageBuffer($this->page, $pstart . $ccode . "\n" . $pend);
					$pagemark += strlen($ccode . "\n");
				}
			}

			$currentY = $this->GetY();
			$this->SetLeftMargin($lMargin);
			$this->SetRightMargin($rMargin);

			if (0 < $ln) {
				$this->SetY($currentY);

				if ($ln == 2) {
					$this->SetX($x + $w);
				}
			}
			else {
				$this->setPage($startpage);
				$this->y = $y;
				$this->SetX($x + $w);
			}

			$this->setContentMark();
			return $nl;
		}

		protected function getBorderMode($border, $position = 'start')
		{
			if (!$this->opencell && ($border == 1)) {
				return 1;
			}

			$cborder = '';

			switch ($position) {
			case 'start':
				if ($border == 1) {
					$cborder = 'LTR';
				}
				else {
					if (!(false === strpos($border, 'L'))) {
						$cborder .= 'L';
					}

					if (!(false === strpos($border, 'T'))) {
						$cborder .= 'T';
					}

					if (!(false === strpos($border, 'R'))) {
						$cborder .= 'R';
					}

					if (!$this->opencell && !(false === strpos($border, 'B'))) {
						$cborder .= 'B';
					}
				}

				break;

			case 'middle':
				if ($border == 1) {
					$cborder = 'LR';
				}
				else {
					if (!(false === strpos($border, 'L'))) {
						$cborder .= 'L';
					}

					if (!$this->opencell && !(false === strpos($border, 'T'))) {
						$cborder .= 'T';
					}

					if (!(false === strpos($border, 'R'))) {
						$cborder .= 'R';
					}

					if (!$this->opencell && !(false === strpos($border, 'B'))) {
						$cborder .= 'B';
					}
				}

				break;

			case 'end':
				if ($border == 1) {
					$cborder = 'LRB';
				}
				else {
					if (!(false === strpos($border, 'L'))) {
						$cborder .= 'L';
					}

					if (!$this->opencell && !(false === strpos($border, 'T'))) {
						$cborder .= 'T';
					}

					if (!(false === strpos($border, 'R'))) {
						$cborder .= 'R';
					}

					if (!(false === strpos($border, 'B'))) {
						$cborder .= 'B';
					}
				}

				break;

			default:
				$cborder = $border;
				break;
			}

			return $cborder;
		}

		public function getNumLines($txt, $w = 0)
		{
			$lines = 0;
			if ($this->empty_string($w) || ($w <= 0)) {
				if ($this->rtl) {
					$w = $this->x - $this->lMargin;
				}
				else {
					$w = $this->w - $this->rMargin - $this->x;
				}
			}

			$wmax = $w - (2 * $this->cMargin);
			$txt = str_replace("\r", '', $txt);

			if (substr($txt, -1) == "\n") {
				$txt = substr($txt, 0, -1);
			}

			$txtblocks = explode("\n", $txt);

			foreach ($txtblocks as $block) {
				$lines += ($this->empty_string($block) ? 1 : ceil($this->GetStringWidth($block) / $wmax));
			}

			return $lines;
		}

		public function Write($h, $txt, $link = '', $fill = 0, $align = '', $ln = false, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0)
		{
			if (strlen($txt) == 0) {
				$txt = ' ';
			}

			$s = str_replace("\r", '', $txt);

			if (preg_match(K_RE_PATTERN_ARABIC, $s)) {
				$arabic = true;
			}
			else {
				$arabic = false;
			}

			if ($arabic || $this->tmprtl || preg_match(K_RE_PATTERN_RTL, $txt)) {
				$rtlmode = true;
			}
			else {
				$rtlmode = false;
			}

			$chrwidth = $this->GetCharWidth('.');
			$chars = $this->UTF8StringToArray($s);
			$uchars = $this->UTF8ArrayToUniArray($chars);
			$nb = count($chars);
			$shy_replacement = 45;
			$shy_replacement_char = $this->unichr($shy_replacement);
			$shy_replacement_width = $this->GetCharWidth($shy_replacement);
			$prevx = $this->x;
			$prevy = $this->y;
			$maxy = ($this->y + $maxh) - $h - (2 * $this->cMargin);

			if ($this->rtl) {
				$w = $this->x - $this->lMargin;
			}
			else {
				$w = $this->w - $this->rMargin - $this->x;
			}

			$wmax = $w - (2 * $this->cMargin);
			if (!$firstline && (($wmax < $chrwidth) || ($wmax < $this->GetCharWidth($chars[0])))) {
				return '';
			}

			$i = 0;
			$j = 0;
			$sep = -1;
			$shy = false;
			$l = 0;
			$nl = 0;
			$linebreak = false;

			while ($i < $nb) {
				if ((0 < $maxh) && ($maxy <= $this->y)) {
					$firstline = true;
				}

				$c = $chars[$i];

				if ($c == 10) {
					if ($align == 'J') {
						if ($this->rtl) {
							$talign = 'R';
						}
						else {
							$talign = 'L';
						}
					}
					else {
						$talign = $align;
					}

					$tmpstr = $this->UniArrSubString($uchars, $j, $i);

					if ($firstline) {
						$startx = $this->x;
						$tmparr = array_slice($chars, $j, $i);

						if ($rtlmode) {
							$tmparr = $this->utf8Bidi($tmparr, $tmpstr, $this->tmprtl);
						}

						$linew = $this->GetArrStringWidth($tmparr);
						unset($tmparr);

						if ($this->rtl) {
							$this->endlinex = $startx - $linew;
						}
						else {
							$this->endlinex = $startx + $linew;
						}

						$w = $linew;
						$tmpcmargin = $this->cMargin;

						if ($maxh == 0) {
							$this->cMargin = 0;
						}
					}

					$this->Cell($w, $h, $tmpstr, 0, 1, $talign, $fill, $link, $stretch);
					unset($tmpstr);

					if ($firstline) {
						$this->cMargin = $tmpcmargin;
						return $this->UniArrSubString($uchars, $i);
					}

					++$nl;
					$j = $i + 1;
					$l = 0;
					$sep = -1;
					$shy = false;
					if (($this->PageBreakTrigger < ($this->y + $this->lasth)) && !$this->InFooter) {
						$this->AcceptPageBreak();
					}

					$w = $this->getRemainingWidth();
					$wmax = $w - (2 * $this->cMargin);
				}
				else {
					if (($c != 160) && (($c == 173) || preg_match($this->re_spaces, $this->unichr($c)))) {
						$sep = $i;

						if ($c == 173) {
							$shy = true;
						}
						else {
							$shy = false;
						}
					}

					if ((($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) && $arabic) {
						$l = $this->GetArrStringWidth($this->utf8Bidi(array_slice($chars, $j, ($i - $j) + 1), '', $this->tmprtl));
					}
					else {
						$l += $this->GetCharWidth($c);
					}

					if (($wmax < $l) || ($shy && ($wmax < ($l + $shy_replacement_width)))) {
						if ($sep == -1) {
							if (($this->rtl && ($this->x <= $this->w - $this->rMargin - $chrwidth)) || (!$this->rtl && (($this->lMargin + $chrwidth) <= $this->x))) {
								$this->Cell($w, $h, '', 0, 1);
								$linebreak = true;

								if ($firstline) {
									return $this->UniArrSubString($uchars, $j);
								}
							}
							else {
								$tmpstr = $this->UniArrSubString($uchars, $j, $i);

								if ($firstline) {
									$startx = $this->x;
									$tmparr = array_slice($chars, $j, $i);

									if ($rtlmode) {
										$tmparr = $this->utf8Bidi($tmparr, $tmpstr, $this->tmprtl);
									}

									$linew = $this->GetArrStringWidth($tmparr);
									unset($tmparr);

									if ($this->rtl) {
										$this->endlinex = $startx - $linew;
									}
									else {
										$this->endlinex = $startx + $linew;
									}

									$w = $linew;
									$tmpcmargin = $this->cMargin;

									if ($maxh == 0) {
										$this->cMargin = 0;
									}
								}

								$this->Cell($w, $h, $tmpstr, 0, 1, $align, $fill, $link, $stretch);
								unset($tmpstr);

								if ($firstline) {
									$this->cMargin = $tmpcmargin;
									return $this->UniArrSubString($uchars, $i);
								}

								$j = $i;
								--$i;
							}
						}
						else {
							if ($this->rtl && !$firstblock) {
								$endspace = 1;
							}
							else {
								$endspace = 0;
							}

							if ($shy) {
								$shy_width = $shy_replacement_width;

								if ($this->rtl) {
									$shy_char_left = $shy_replacement_char;
									$shy_char_right = '';
								}
								else {
									$shy_char_left = '';
									$shy_char_right = $shy_replacement_char;
								}
							}
							else {
								$shy_width = 0;
								$shy_char_left = '';
								$shy_char_right = '';
							}

							$tmpstr = $this->UniArrSubString($uchars, $j, $sep + $endspace);

							if ($firstline) {
								$startx = $this->x;
								$tmparr = array_slice($chars, $j, $sep + $endspace);

								if ($rtlmode) {
									$tmparr = $this->utf8Bidi($tmparr, $tmpstr, $this->tmprtl);
								}

								$linew = $this->GetArrStringWidth($tmparr);
								unset($tmparr);

								if ($this->rtl) {
									$this->endlinex = $startx - $linew - $shy_width;
								}
								else {
									$this->endlinex = $startx + $linew + $shy_width;
								}

								$w = $linew;
								$tmpcmargin = $this->cMargin;

								if ($maxh == 0) {
									$this->cMargin = 0;
								}
							}

							$this->Cell($w, $h, $shy_char_left . $tmpstr . $shy_char_right, 0, 1, $align, $fill, $link, $stretch);
							unset($tmpstr);

							if ($firstline) {
								$this->cMargin = $tmpcmargin;
								return $this->UniArrSubString($uchars, $sep + $endspace);
							}

							$i = $sep;
							$sep = -1;
							$shy = false;
							$j = $i + 1;
						}

						if (($this->PageBreakTrigger < ($this->y + $this->lasth)) && !$this->InFooter) {
							$this->AcceptPageBreak();
						}

						$w = $this->getRemainingWidth();
						$wmax = $w - (2 * $this->cMargin);

						if ($linebreak) {
							$linebreak = false;
						}
						else {
							++$nl;
							$l = 0;
						}
					}
				}

				++$i;
			}

			if (0 < $l) {
				switch ($align) {
				case 'J':
				case 'C':
					$w = $w;
					break;

				case 'L':
					if ($this->rtl) {
						$w = $w;
					}
					else {
						$w = $l;
					}

					break;

				case 'R':
					if ($this->rtl) {
						$w = $l;
					}
					else {
						$w = $w;
					}

					break;

				default:
					$w = $l;
					break;
				}

				$tmpstr = $this->UniArrSubString($uchars, $j, $nb);

				if ($firstline) {
					$startx = $this->x;
					$tmparr = array_slice($chars, $j, $nb);

					if ($rtlmode) {
						$tmparr = $this->utf8Bidi($tmparr, $tmpstr, $this->tmprtl);
					}

					$linew = $this->GetArrStringWidth($tmparr);
					unset($tmparr);

					if ($this->rtl) {
						$this->endlinex = $startx - $linew;
					}
					else {
						$this->endlinex = $startx + $linew;
					}

					$w = $linew;
					$tmpcmargin = $this->cMargin;

					if ($maxh == 0) {
						$this->cMargin = 0;
					}
				}

				$this->Cell($w, $h, $tmpstr, 0, $ln, $align, $fill, $link, $stretch);
				unset($tmpstr);

				if ($firstline) {
					$this->cMargin = $tmpcmargin;
					return $this->UniArrSubString($uchars, $nb);
				}

				++$nl;
			}

			if ($firstline) {
				return '';
			}

			return $nl;
		}

		protected function getRemainingWidth()
		{
			if ($this->rtl) {
				return $this->x - $this->lMargin;
			}
			else {
				return $this->w - $this->rMargin - $this->x;
			}
		}

		public function UTF8ArrSubString($strarr, $start = '', $end = '')
		{
			if (strlen($start) == 0) {
				$start = 0;
			}

			if (strlen($end) == 0) {
				$end = count($strarr);
			}

			$string = '';

			for ($i = $start; $i < $end; ++$i) {
				$string .= $this->unichr($strarr[$i]);
			}

			return $string;
		}

		public function UniArrSubString($uniarr, $start = '', $end = '')
		{
			if (strlen($start) == 0) {
				$start = 0;
			}

			if (strlen($end) == 0) {
				$end = count($uniarr);
			}

			$string = '';

			for ($i = $start; $i < $end; ++$i) {
				$string .= $uniarr[$i];
			}

			return $string;
		}

		public function UTF8ArrayToUniArray($ta)
		{
			return array_map(array($this, 'unichr'), $ta);
		}

		public function unichr($c)
		{
			if (!$this->isunicode) {
				return chr($c);
			}
			else if ($c <= 127) {
				return chr($c);
			}
			else if ($c <= 2047) {
				return chr(192 | ($c >> 6)) . chr(128 | ($c & 63));
			}
			else if ($c <= 65535) {
				return chr(224 | ($c >> 12)) . chr(128 | (($c >> 6) & 63)) . chr(128 | ($c & 63));
			}
			else if ($c <= 1114111) {
				return chr(240 | ($c >> 18)) . chr(128 | (($c >> 12) & 63)) . chr(128 | (($c >> 6) & 63)) . chr(128 | ($c & 63));
			}
			else {
				return '';
			}
		}

		public function Image($file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			$imsize = @getimagesize($file);

			if ($imsize === false) {
				$file = str_replace(' ', '%20', $file);
				$imsize = @getimagesize($file);

				if ($imsize === false) {
					$this->Error('[Image] No such file or directory in ' . $file);
				}
			}

			list($pixw, $pixh) = $imsize;
			if (($w <= 0) && ($h <= 0)) {
				$w = $this->pixelsToUnits($pixw);
				$h = $this->pixelsToUnits($pixh);
			}
			else if ($w <= 0) {
				$w = ($h * $pixw) / $pixh;
			}
			else if ($h <= 0) {
				$h = ($w * $pixh) / $pixw;
			}
			else {
				if ($fitbox && (0 < $w) && (0 < $h)) {
					if ((($w * $pixh) / ($h * $pixw)) < 1) {
						$h = ($w * $pixh) / $pixw;
					}
					else {
						$w = ($h * $pixw) / $pixh;
					}
				}
			}

			$neww = round(($w * $this->k * $dpi) / $this->dpi);
			$newh = round(($h * $this->k * $dpi) / $this->dpi);

			if (($pixw * $pixh) <= $neww * $newh) {
				$resize = false;
			}

			if (!in_array($file, $this->imagekeys)) {
				if ($type == '') {
					$fileinfo = pathinfo($file);
					if (isset($fileinfo['extension']) && !$this->empty_string($fileinfo['extension'])) {
						$type = $fileinfo['extension'];
					}
					else {
						$this->Error('Image file has no extension and no type was specified: ' . $file);
					}
				}

				$type = strtolower($type);

				if ($type == 'jpg') {
					$type = 'jpeg';
				}

				$mqr = $this->get_mqr();
				$this->set_mqr(false);
				$mtd = '_parse' . $type;
				$gdfunction = 'imagecreatefrom' . $type;
				$info = false;
				if (method_exists($this, $mtd) && !($resize && function_exists($gdfunction))) {
					$info = $this->$mtd($file);

					if ($info == 'pngalpha') {
						return $this->ImagePngAlpha($file, $x, $y, $w, $h, 'PNG', $link, $align, $resize, $dpi, $palign);
					}
				}

				if (!$info) {
					if (function_exists($gdfunction)) {
						$img = $gdfunction($file);

						if ($resize) {
							$imgr = imagecreatetruecolor($neww, $newh);
							imagecopyresampled($imgr, $img, 0, 0, 0, 0, $neww, $newh, $pixw, $pixh);
							$info = $this->_toJPEG($imgr);
						}
						else {
							$info = $this->_toJPEG($img);
						}
					}
					else if (extension_loaded('imagick')) {
						$img = new Imagick();
						$img->readImage($file);

						if ($resize) {
							$img->resizeImage($neww, $newh, 10, 1, false);
						}

						$img->setCompressionQuality($this->jpeg_quality);
						$img->setImageFormat('jpeg');
						$tempname = tempnam(K_PATH_CACHE, 'jpg_');
						$img->writeImage($tempname);
						$info = $this->_parsejpeg($tempname);
						unlink($tempname);
						$img->destroy();
					}
					else {
						return NULL;
					}
				}

				if ($info === false) {
					return NULL;
				}

				$this->set_mqr($mqr);

				if ($ismask) {
					$info['cs'] = 'DeviceGray';
				}

				$info['i'] = $this->numimages + 1;

				if ($imgmask !== false) {
					$info['masked'] = $imgmask;
				}

				$this->setImageBuffer($file, $info);
			}
			else {
				$info = $this->getImageBuffer($file);
			}

			if ($this->checkPageBreak($h, $y)) {
				$y = $this->GetY() + $this->cMargin;
			}

			$this->img_rb_y = $y + $h;

			if ($this->rtl) {
				if ($palign == 'L') {
					$ximg = $this->lMargin;
					$this->img_rb_x = $ximg + $w;
				}
				else if ($palign == 'C') {
					$ximg = ($this->w - $x - $w) / 2;
					$this->img_rb_x = $ximg + $w;
				}
				else {
					$ximg = $this->w - $x - $w;
					$this->img_rb_x = $ximg;
				}
			}
			else if ($palign == 'R') {
				$ximg = $this->w - $this->rMargin - $w;
				$this->img_rb_x = $ximg;
			}
			else if ($palign == 'C') {
				$ximg = ($this->w - $x - $w) / 2;
				$this->img_rb_x = $ximg + $w;
			}
			else {
				$ximg = $x;
				$this->img_rb_x = $ximg + $w;
			}

			if ($ismask || $hidden) {
				return $info['i'];
			}

			$xkimg = $ximg * $this->k;
			$this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q', $w * $this->k, $h * $this->k, $xkimg, ($this->h - ($y + $h)) * $this->k, $info['i']));

			if (!empty($border)) {
				$bx = $x;
				$by = $y;
				$this->x = $ximg;
				$this->y = $y;
				$this->Cell($w, $h, '', $border, 0, '', 0, '', 0);
				$this->x = $bx;
				$this->y = $by;
			}

			if ($link) {
				$this->Link($ximg, $y, $w, $h, $link, 0);
			}

			switch ($align) {
			case 'T':
				$this->y = $y;
				$this->x = $this->img_rb_x;
				break;

			case 'M':
				$this->y = $y + round($h / 2);
				$this->x = $this->img_rb_x;
				break;

			case 'B':
				$this->y = $this->img_rb_y;
				$this->x = $this->img_rb_x;
				break;

			case 'N':
				$this->SetY($this->img_rb_y);
				break;

			default:
				break;
			}

			$this->endlinex = $this->img_rb_x;
			return $info['i'];
		}

		public function set_mqr($mqr)
		{
			if (!defined('PHP_VERSION_ID')) {
				$version = PHP_VERSION;
				define('PHP_VERSION_ID', ($version[0] * 10000) + ($version[2] * 100) + $version[4]);
			}

			if (PHP_VERSION_ID < 50300) {
				@set_magic_quotes_runtime($mqr);
			}
		}

		public function get_mqr()
		{
			if (!defined('PHP_VERSION_ID')) {
				$version = PHP_VERSION;
				define('PHP_VERSION_ID', ($version[0] * 10000) + ($version[2] * 100) + $version[4]);
			}

			if (PHP_VERSION_ID < 50300) {
				return @get_magic_quotes_runtime();
			}

			return 0;
		}

		protected function _toJPEG($image)
		{
			$tempname = tempnam(K_PATH_CACHE, 'jpg_');
			imagejpeg($image, $tempname, $this->jpeg_quality);
			imagedestroy($image);
			$retvars = $this->_parsejpeg($tempname);
			unlink($tempname);
			return $retvars;
		}

		protected function _parsejpeg($file)
		{
			$a = getimagesize($file);

			if (empty($a)) {
				$this->Error('Missing or incorrect image file: ' . $file);
			}

			if ($a[2] != 2) {
				$this->Error('Not a JPEG file: ' . $file);
			}

			if (!isset($a['channels']) || ($a['channels'] == 3)) {
				$colspace = 'DeviceRGB';
			}
			else if ($a['channels'] == 4) {
				$colspace = 'DeviceCMYK';
			}
			else {
				$colspace = 'DeviceGray';
			}

			$bpc = (isset($a['bits']) ? $a['bits'] : 8);
			$data = file_get_contents($file);
			return array('w' => $a[0], 'h' => $a[1], 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'DCTDecode', 'data' => $data);
		}

		protected function _parsepng($file)
		{
			$f = fopen($file, 'rb');

			if ($f === false) {
				$this->Error('Can\'t open image file: ' . $file);
			}

			if (fread($f, 8) != chr(137) . 'PNG' . chr(13) . chr(10) . chr(26) . chr(10)) {
				$this->Error('Not a PNG file: ' . $file);
			}

			fread($f, 4);

			if (fread($f, 4) != 'IHDR') {
				$this->Error('Incorrect PNG file: ' . $file);
			}

			$w = $this->_freadint($f);
			$h = $this->_freadint($f);
			$bpc = ord(fread($f, 1));

			if (8 < $bpc) {
				fclose($f);
				return false;
			}

			$ct = ord(fread($f, 1));

			if ($ct == 0) {
				$colspace = 'DeviceGray';
			}
			else if ($ct == 2) {
				$colspace = 'DeviceRGB';
			}
			else if ($ct == 3) {
				$colspace = 'Indexed';
			}
			else {
				fclose($f);
				return 'pngalpha';
			}

			if (ord(fread($f, 1)) != 0) {
				fclose($f);
				return false;
			}

			if (ord(fread($f, 1)) != 0) {
				fclose($f);
				return false;
			}

			if (ord(fread($f, 1)) != 0) {
				fclose($f);
				return false;
			}

			fread($f, 4);
			$parms = '/DecodeParms <</Predictor 15 /Colors ' . ($ct == 2 ? 3 : 1) . ' /BitsPerComponent ' . $bpc . ' /Columns ' . $w . '>>';
			$pal = '';
			$trns = '';
			$data = '';

			do {
				$n = $this->_freadint($f);
				$type = fread($f, 4);

				if ($type == 'PLTE') {
					$pal = $this->rfread($f, $n);
					fread($f, 4);
				}
				else if ($type == 'tRNS') {
					$t = $this->rfread($f, $n);

					if ($ct == 0) {
						$trns = array(ord(substr($t, 1, 1)));
					}
					else if ($ct == 2) {
						$trns = array(ord(substr($t, 1, 1)), ord(substr($t, 3, 1)), ord(substr($t, 5, 1)));
					}
					else {
						$pos = strpos($t, chr(0));

						if ($pos !== false) {
							$trns = array($pos);
						}
					}

					fread($f, 4);
				}
				else if ($type == 'IDAT') {
					$data .= $this->rfread($f, $n);
					fread($f, 4);
				}
				else if ($type == 'IEND') {
					break;
				}
				else {
					$this->rfread($f, $n + 4);
				}
			} while ($n);

			if (($colspace == 'Indexed') && empty($pal)) {
				fclose($f);
				return false;
			}

			fclose($f);
			return array('w' => $w, 'h' => $h, 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'FlateDecode', 'parms' => $parms, 'pal' => $pal, 'trns' => $trns, 'data' => $data);
		}

		protected function rfread($handle, $length)
		{
			$data = fread($handle, $length);

			if ($data === false) {
				return false;
			}

			$rest = $length - strlen($data);

			if (0 < $rest) {
				$data .= $this->rfread($handle, $rest);
			}

			return $data;
		}

		protected function ImagePngAlpha($file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '')
		{
			list($wpx, $hpx) = getimagesize($file);
			$img = imagecreatefrompng($file);
			$imgalpha = imagecreate($wpx, $hpx);

			for ($c = 0; $c < 256; ++$c) {
				imagecolorallocate($imgalpha, $c, $c, $c);
			}

			for ($xpx = 0; $xpx < $wpx; ++$xpx) {
				for ($ypx = 0; $ypx < $hpx; ++$ypx) {
					$colorindex = imagecolorat($img, $xpx, $ypx);
					$col = imagecolorsforindex($img, $colorindex);
					imagesetpixel($imgalpha, $xpx, $ypx, $this->getGDgamma(((127 - $col['alpha']) * 255) / 127));
				}
			}

			$tempfile_alpha = tempnam(K_PATH_CACHE, 'mska_');
			imagepng($imgalpha, $tempfile_alpha);
			imagedestroy($imgalpha);
			$imgplain = imagecreatetruecolor($wpx, $hpx);
			imagecopy($imgplain, $img, 0, 0, 0, 0, $wpx, $hpx);
			$tempfile_plain = tempnam(K_PATH_CACHE, 'mskp_');
			imagepng($imgplain, $tempfile_plain);
			imagedestroy($imgplain);
			$imgmask = $this->Image($tempfile_alpha, $x, $y, $w, $h, 'PNG', '', '', $resize, $dpi, '', true, false);
			$this->Image($tempfile_plain, $x, $y, $w, $h, $type, $link, $align, $resize, $dpi, $palign, false, $imgmask);
			unlink($tempfile_alpha);
			unlink($tempfile_plain);
		}

		protected function getGDgamma($v)
		{
			return pow($v / 255, 2.2) * 255;
		}

		public function Ln($h = '', $cell = false)
		{
			if ($cell) {
				$cellmargin = $this->cMargin;
			}
			else {
				$cellmargin = 0;
			}

			if ($this->rtl) {
				$this->x = $this->w - $this->rMargin - $cellmargin;
			}
			else {
				$this->x = $this->lMargin + $cellmargin;
			}

			if (is_string($h)) {
				$this->y += $this->lasth;
			}
			else {
				$this->y += $h;
			}

			$this->newline = true;
		}

		public function GetX()
		{
			if ($this->rtl) {
				return $this->w - $this->x;
			}
			else {
				return $this->x;
			}
		}

		public function GetAbsX()
		{
			return $this->x;
		}

		public function GetY()
		{
			return $this->y;
		}

		public function SetX($x)
		{
			if ($this->rtl) {
				if (0 <= $x) {
					$this->x = $this->w - $x;
				}
				else {
					$this->x = abs($x);
				}
			}
			else if (0 <= $x) {
				$this->x = $x;
			}
			else {
				$this->x = $this->w + $x;
			}

			if ($this->x < 0) {
				$this->x = 0;
			}

			if ($this->w < $this->x) {
				$this->x = $this->w;
			}
		}

		public function SetY($y, $resetx = true)
		{
			if ($resetx) {
				if ($this->rtl) {
					$this->x = $this->w - $this->rMargin;
				}
				else {
					$this->x = $this->lMargin;
				}
			}

			if (0 <= $y) {
				$this->y = $y;
			}
			else {
				$this->y = $this->h + $y;
			}

			if ($this->y < 0) {
				$this->y = 0;
			}

			if ($this->h < $this->y) {
				$this->y = $this->h;
			}
		}

		public function SetXY($x, $y)
		{
			$this->SetY($y);
			$this->SetX($x);
		}

		public function Output($name = 'doc.pdf', $dest = 'I')
		{
			if ($this->state < 3) {
				$this->Close();
			}

			if (is_bool($dest)) {
				$dest = ($dest ? 'D' : 'F');
			}

			$dest = strtoupper($dest);

			if ($dest != 'F') {
				$name = preg_replace('/[\\s]+/', '_', $name);
				$name = preg_replace('/[^a-zA-Z0-9_\\.-]/', '', $name);
			}

			if ($this->sign) {
				$pdfdoc = $this->getBuffer();
				$pdfdoc = substr($pdfdoc, 0, -1);
				if (isset($this->diskcache) && $this->diskcache) {
					unlink($this->buffer);
				}

				unset($this->buffer);
				$byterange_string_len = strlen($this->byterange_string);
				$byte_range = array();
				$byte_range[0] = 0;
				$byte_range[1] = strpos($pdfdoc, $this->byterange_string) + $byterange_string_len + 10;
				$byte_range[2] = $byte_range[1] + $this->signature_max_lenght + 2;
				$byte_range[3] = strlen($pdfdoc) - $byte_range[2];
				$pdfdoc = substr($pdfdoc, 0, $byte_range[1]) . substr($pdfdoc, $byte_range[2]);
				$byterange = sprintf('/ByteRange[0 %u %u %u]', $byte_range[1], $byte_range[2], $byte_range[3]);
				$byterange .= str_repeat(' ', $byterange_string_len - strlen($byterange));
				$pdfdoc = str_replace($this->byterange_string, $byterange, $pdfdoc);
				$tempdoc = tempnam(K_PATH_CACHE, 'tmppdf_');
				$f = fopen($tempdoc, 'wb');

				if (!$f) {
					$this->Error('Unable to create temporary file: ' . $tempdoc);
				}

				$pdfdoc_lenght = strlen($pdfdoc);
				fwrite($f, $pdfdoc, $pdfdoc_lenght);
				fclose($f);
				$tempsign = tempnam(K_PATH_CACHE, 'tmpsig_');

				if (empty($this->signature_data['extracerts'])) {
					openssl_pkcs7_sign($tempdoc, $tempsign, $this->signature_data['signcert'], array($this->signature_data['privkey'], $this->signature_data['password']), array(), PKCS7_BINARY | PKCS7_DETACHED);
				}
				else {
					openssl_pkcs7_sign($tempdoc, $tempsign, $this->signature_data['signcert'], array($this->signature_data['privkey'], $this->signature_data['password']), array(), PKCS7_BINARY | PKCS7_DETACHED, $this->signature_data['extracerts']);
				}

				unlink($tempdoc);
				$signature = file_get_contents($tempsign, false, NULL, $pdfdoc_lenght);
				unlink($tempsign);
				$signature = substr($signature, strpos($signature, '%%EOF' . "\n" . '' . "\n" . '------') + 13);
				$tmparr = explode('' . "\n" . '' . "\n" . '', $signature);
				$signature = $tmparr[1];
				unset($tmparr);
				$signature = base64_decode(trim($signature));
				$signature = current(unpack('H*', $signature));
				$signature = str_pad($signature, $this->signature_max_lenght, '0');
				$pdfdoc = substr($pdfdoc, 0, $byte_range[1]) . '<' . $signature . '>' . substr($pdfdoc, $byte_range[1]);
				$this->diskcache = false;
				$this->buffer = &$pdfdoc;
				$this->bufferlen = strlen($pdfdoc);
			}

			switch ($dest) {
			case 'I':
				if (ob_get_contents()) {
					$this->Error('Some data has already been output, can\'t send PDF file');
				}

				if (php_sapi_name() != 'cli') {
					header('Content-Type: application/pdf');

					if (headers_sent()) {
						$this->Error('Some data has already been output to browser, can\'t send PDF file');
					}

					header('Cache-Control: public, must-revalidate, max-age=0');
					header('Pragma: public');
					header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
					header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
					header('Content-Length: ' . $this->bufferlen);
					header('Content-Disposition: inline; filename="' . basename($name) . '";');
				}

				echo $this->getBuffer();
				break;

			case 'D':
				if (ob_get_contents()) {
					$this->Error('Some data has already been output, can\'t send PDF file');
				}

				header('Content-Description: File Transfer');

				if (headers_sent()) {
					$this->Error('Some data has already been output to browser, can\'t send PDF file');
				}

				header('Cache-Control: public, must-revalidate, max-age=0');
				header('Pragma: public');
				header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
				header('Content-Type: application/force-download');
				header('Content-Type: application/octet-stream', false);
				header('Content-Type: application/download', false);
				header('Content-Type: application/pdf', false);
				header('Content-Disposition: attachment; filename="' . basename($name) . '";');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . $this->bufferlen);
				echo $this->getBuffer();
				break;

			case 'F':
				if ($this->diskcache) {
					copy($this->buffer, $name);
				}
				else {
					$f = fopen($name, 'wb');

					if (!$f) {
						$this->Error('Unable to create output file: ' . $name);
					}

					fwrite($f, $this->getBuffer(), $this->bufferlen);
					fclose($f);
				}

				break;

			case 'S':
				return $this->getBuffer();
			default:
				$this->Error('Incorrect output destination: ' . $dest);
			}

			return '';
		}

		public function _destroy($destroyall = false, $preserve_objcopy = false)
		{
			if ($destroyall && isset($this->diskcache) && $this->diskcache && !$preserve_objcopy && !$this->empty_string($this->buffer)) {
				unlink($this->buffer);
			}

			foreach (array_keys(get_object_vars($this)) as $val) {
				if ($destroyall || (($val != 'internal_encoding') && ($val != 'state') && ($val != 'bufferlen') && ($val != 'buffer') && ($val != 'diskcache') && ($val != 'sign') && ($val != 'signature_data') && ($val != 'signature_max_lenght') && ($val != 'byterange_string'))) {
					if (!$preserve_objcopy || ($val != 'objcopy')) {
						unset($this->$val);
					}
				}
			}
		}

		protected function _dochecks()
		{
			if (1.1 == 1) {
				$this->Error('Don\'t alter the locale before including class file');
			}

			if (sprintf('%.1F', 1) != '1.0') {
				setlocale(LC_NUMERIC, 'C');
			}
		}

		protected function _getfontpath()
		{
			if (!defined('K_PATH_FONTS') && is_dir(dirname(__FILE__) . '/fonts')) {
				define('K_PATH_FONTS', dirname(__FILE__) . '/fonts/');
			}

			return defined('K_PATH_FONTS') ? K_PATH_FONTS : '';
		}

		protected function _putpages()
		{
			$nb = $this->numpages;

			if (!empty($this->AliasNbPages)) {
				$nbs = $this->formatPageNumber($nb);
				$nbu = $this->UTF8ToUTF16BE($nbs, false);
				$alias_a = $this->_escape($this->AliasNbPages);
				$alias_au = $this->_escape('{' . $this->AliasNbPages . '}');

				if ($this->isunicode) {
					$alias_b = $this->_escape($this->UTF8ToLatin1($this->AliasNbPages));
					$alias_bu = $this->_escape($this->UTF8ToLatin1('{' . $this->AliasNbPages . '}'));
					$alias_c = $this->_escape($this->utf8StrRev($this->AliasNbPages, false, $this->tmprtl));
					$alias_cu = $this->_escape($this->utf8StrRev('{' . $this->AliasNbPages . '}', false, $this->tmprtl));
				}
			}

			if (!empty($this->AliasNumPage)) {
				$alias_pa = $this->_escape($this->AliasNumPage);
				$alias_pau = $this->_escape('{' . $this->AliasNumPage . '}');

				if ($this->isunicode) {
					$alias_pb = $this->_escape($this->UTF8ToLatin1($this->AliasNumPage));
					$alias_pbu = $this->_escape($this->UTF8ToLatin1('{' . $this->AliasNumPage . '}'));
					$alias_pc = $this->_escape($this->utf8StrRev($this->AliasNumPage, false, $this->tmprtl));
					$alias_pcu = $this->_escape($this->utf8StrRev('{' . $this->AliasNumPage . '}', false, $this->tmprtl));
				}
			}

			$pagegroupnum = 0;
			$filter = ($this->compress ? '/Filter /FlateDecode ' : '');

			for ($n = 1; $n <= $nb; ++$n) {
				$temppage = $this->getPageBuffer($n);

				if (!empty($this->pagegroups)) {
					if (isset($this->newpagegroup[$n])) {
						$pagegroupnum = 0;
					}

					++$pagegroupnum;

					foreach ($this->pagegroups as $k => $v) {
						$vs = $this->formatPageNumber($v);
						$vu = $this->UTF8ToUTF16BE($vs, false);
						$alias_ga = $this->_escape($k);
						$alias_gau = $this->_escape('{' . $k . '}');

						if ($this->isunicode) {
							$alias_gb = $this->_escape($this->UTF8ToLatin1($k));
							$alias_gbu = $this->_escape($this->UTF8ToLatin1('{' . $k . '}'));
							$alias_gc = $this->_escape($this->utf8StrRev($k, false, $this->tmprtl));
							$alias_gcu = $this->_escape($this->utf8StrRev('{' . $k . '}', false, $this->tmprtl));
						}

						$temppage = str_replace($alias_gau, $vu, $temppage);

						if ($this->isunicode) {
							$temppage = str_replace($alias_gbu, $vu, $temppage);
							$temppage = str_replace($alias_gcu, $vu, $temppage);
							$temppage = str_replace($alias_gb, $vs, $temppage);
							$temppage = str_replace($alias_gc, $vs, $temppage);
						}

						$temppage = str_replace($alias_ga, $vs, $temppage);
						$pvs = $this->formatPageNumber($pagegroupnum);
						$pvu = $this->UTF8ToUTF16BE($pvs, false);
						$pk = str_replace('{nb', '{pnb', $k);
						$alias_pga = $this->_escape($pk);
						$alias_pgau = $this->_escape('{' . $pk . '}');

						if ($this->isunicode) {
							$alias_pgb = $this->_escape($this->UTF8ToLatin1($pk));
							$alias_pgbu = $this->_escape($this->UTF8ToLatin1('{' . $pk . '}'));
							$alias_pgc = $this->_escape($this->utf8StrRev($pk, false, $this->tmprtl));
							$alias_pgcu = $this->_escape($this->utf8StrRev('{' . $pk . '}', false, $this->tmprtl));
						}

						$temppage = str_replace($alias_pgau, $pvu, $temppage);

						if ($this->isunicode) {
							$temppage = str_replace($alias_pgbu, $pvu, $temppage);
							$temppage = str_replace($alias_pgcu, $pvu, $temppage);
							$temppage = str_replace($alias_pgb, $pvs, $temppage);
							$temppage = str_replace($alias_pgc, $pvs, $temppage);
						}

						$temppage = str_replace($alias_pga, $pvs, $temppage);
					}
				}

				if (!empty($this->AliasNbPages)) {
					$temppage = str_replace($alias_au, $nbu, $temppage);

					if ($this->isunicode) {
						$temppage = str_replace($alias_bu, $nbu, $temppage);
						$temppage = str_replace($alias_cu, $nbu, $temppage);
						$temppage = str_replace($alias_b, $nbs, $temppage);
						$temppage = str_replace($alias_c, $nbs, $temppage);
					}

					$temppage = str_replace($alias_a, $nbs, $temppage);
				}

				if (!empty($this->AliasNumPage)) {
					$pnbs = $this->formatPageNumber($n);
					$pnbu = $this->UTF8ToUTF16BE($pnbs, false);
					$temppage = str_replace($alias_pau, $pnbu, $temppage);

					if ($this->isunicode) {
						$temppage = str_replace($alias_pbu, $pnbu, $temppage);
						$temppage = str_replace($alias_pcu, $pnbu, $temppage);
						$temppage = str_replace($alias_pb, $pnbs, $temppage);
						$temppage = str_replace($alias_pc, $pnbs, $temppage);
					}

					$temppage = str_replace($alias_pa, $pnbs, $temppage);
				}

				$temppage = str_replace($this->epsmarker, '', $temppage);
				$this->page_obj_id[$n] = $this->_newobj();
				$this->_out('<</Type /Page');
				$this->_out('/Parent 1 0 R');
				$this->_out(sprintf('/MediaBox [0 0 %.2F %.2F]', $this->pagedim[$n]['w'], $this->pagedim[$n]['h']));
				$this->_out('/Resources 2 0 R');
				$this->_putannotsrefs($n);
				$this->_out('/Contents ' . ($this->n + 1) . ' 0 R>>');
				$this->_out('endobj');
				$p = ($this->compress ? gzcompress($temppage) : $temppage);
				$this->_newobj();
				$this->_out('<<' . $filter . '/Length ' . strlen($p) . '>>');
				$this->_putstream($p);
				$this->_out('endobj');

				if ($this->diskcache) {
					unlink($this->pages[$n]);
				}
			}

			$this->offsets[1] = $this->bufferlen;
			$this->_out('1 0 obj');
			$this->_out('<</Type /Pages');
			$this->_out('/Kids [');

			foreach ($this->page_obj_id as $page_obj) {
				$this->_out($page_obj . ' 0 R');
			}

			$this->_out(']');
			$this->_out('/Count ' . $nb);
			$this->_out('>>');
			$this->_out('endobj');
		}

		protected function _putannotsrefs($n)
		{
			if (!(isset($this->PageAnnots[$n]) || ($this->sign && isset($this->signature_data['cert_type'])))) {
				return NULL;
			}

			$this->_out('/Annots [');

			if (isset($this->PageAnnots[$n])) {
				$num_annots = count($this->PageAnnots[$n]);

				for ($i = 0; $i < $num_annots; ++$i) {
					++$this->curr_annot_obj_id;

					if (!in_array($this->curr_annot_obj_id, $this->radio_groups)) {
						$this->_out($this->curr_annot_obj_id . ' 0 R');
					}
					else {
						++$num_annots;
					}
				}
			}

			if (($n == 1) && $this->sign && isset($this->signature_data['cert_type'])) {
				$this->_out($this->sig_annot_ref);
			}

			$this->_out(']');
		}

		protected function _putannotsobjs()
		{
			$this->annot_obj_id = $this->annots_start_obj_id;

			for ($n = 1; $n <= $this->numpages; ++$n) {
				if (isset($this->PageAnnots[$n])) {
					foreach ($this->PageAnnots[$n] as $key => $pl) {
						if (isset($this->radiobutton_groups[$n][$pl['txt']]) && is_array($this->radiobutton_groups[$n][$pl['txt']])) {
							$annots = '<<';
							$annots .= ' /Type /Annot';
							$annots .= ' /Subtype /Widget';
							$annots .= ' /T ' . $this->_datastring($pl['txt']);
							$annots .= ' /FT /Btn';
							$annots .= ' /Ff 49152';
							$annots .= ' /Kids [';

							foreach ($this->radiobutton_groups[$n][$pl['txt']] as $data) {
								$annots .= ' ' . $data['kid'] . ' 0 R';

								if ($data['def'] !== 'Off') {
									$defval = $data['def'];
								}
							}

							$annots .= ' ]';

							if (isset($defval)) {
								$annots .= ' /V /' . $defval;
							}

							$annots .= ' >>';
							++$this->annot_obj_id;
							$this->offsets[$this->annot_obj_id] = $this->bufferlen;
							$this->_out($this->annot_obj_id . ' 0 obj');
							$this->_out($annots);
							$this->_out('endobj');
							$this->form_obj_id[] = $this->annot_obj_id;
							$this->radiobutton_groups[$n][$pl['txt']] = $this->annot_obj_id;
						}

						$formfield = false;
						$pl['opt'] = array_change_key_case($pl['opt'], CASE_LOWER);
						$a = $pl['x'] * $this->k;
						$b = $this->pagedim[$n]['h'] - (($pl['y'] + $pl['h']) * $this->k);
						$c = $pl['w'] * $this->k;
						$d = $pl['h'] * $this->k;
						$rect = sprintf('%.2F %.2F %.2F %.2F', $a, $b, $a + $c, $b + $d);
						$annots = '<</Type /Annot';
						$annots .= ' /Subtype /' . $pl['opt']['subtype'];
						$annots .= ' /Rect [' . $rect . ']';
						$ft = array('Btn', 'Tx', 'Ch', 'Sig');
						if (isset($pl['opt']['ft']) && in_array($pl['opt']['ft'], $ft)) {
							$annots .= ' /FT /' . $pl['opt']['ft'];
							$formfield = true;
						}

						$annots .= ' /Contents ' . $this->_textstring($pl['txt']);
						$annots .= ' /P ' . $this->page_obj_id[$n] . ' 0 R';
						$annots .= ' /NM ' . $this->_datastring(sprintf('%04u-%04u', $n, $key));
						$annots .= ' /M ' . $this->_datestring();

						if (isset($pl['opt']['f'])) {
							$val = 0;

							if (is_array($pl['opt']['f'])) {
								foreach ($pl['opt']['f'] as $f) {
									switch (strtolower($f)) {
									case 'invisible':
										$val += 1 << 0;
										break;

									case 'hidden':
										$val += 1 << 1;
										break;

									case 'print':
										$val += 1 << 2;
										break;

									case 'nozoom':
										$val += 1 << 3;
										break;

									case 'norotate':
										$val += 1 << 4;
										break;

									case 'noview':
										$val += 1 << 5;
										break;

									case 'readonly':
										$val += 1 << 6;
										break;

									case 'locked':
										$val += 1 << 8;
										break;

									case 'togglenoview':
										$val += 1 << 9;
										break;

									case 'lockedcontents':
										$val += 1 << 10;
										break;

									default:
										break;
									}
								}
							}
							else {
								$val = intval($pl['opt']['f']);
							}

							$annots .= ' /F ' . intval($val);
						}

						if (isset($pl['opt']['as']) && is_string($pl['opt']['as'])) {
							$annots .= ' /AS /' . $pl['opt']['as'];
						}

						if (isset($pl['opt']['ap'])) {
							$annots .= ' /AP <<';

							if (is_array($pl['opt']['ap'])) {
								foreach ($pl['opt']['ap'] as $apmode => $apdef) {
									$annots .= ' /' . strtoupper($apmode);

									if (is_array($apdef)) {
										$annots .= ' <<';

										foreach ($apdef as $apstate => $stream) {
											$apsobjid = $this->_putAPXObject($c, $d, $stream);
											$annots .= ' /' . $apstate . ' ' . $apsobjid . ' 0 R';
										}

										$annots .= ' >>';
									}
									else {
										$apsobjid = $this->_putAPXObject($c, $d, $apdef);
										$annots .= ' ' . $apsobjid . ' 0 R';
									}
								}
							}
							else {
								$annots .= $pl['opt']['ap'];
							}

							$annots .= ' >>';
						}

						if (isset($pl['opt']['bs']) && is_array($pl['opt']['bs'])) {
							$annots .= ' /BS <<';
							$annots .= ' /Type /Border';

							if (isset($pl['opt']['bs']['w'])) {
								$annots .= ' /W ' . intval($pl['opt']['bs']['w']);
							}

							$bstyles = array('S', 'D', 'B', 'I', 'U');
							if (isset($pl['opt']['bs']['s']) && in_array($pl['opt']['bs']['s'], $bstyles)) {
								$annots .= ' /S /' . $pl['opt']['bs']['s'];
							}

							if (isset($pl['opt']['bs']['d']) && is_array($pl['opt']['bs']['d'])) {
								$annots .= ' /D [';

								foreach ($pl['opt']['bs']['d'] as $cord) {
									$annots .= ' ' . intval($cord);
								}

								$annots .= ']';
							}

							$annots .= ' >>';
						}
						else {
							$annots .= ' /Border [';
							if (isset($pl['opt']['border']) && (3 <= count($pl['opt']['border']))) {
								$annots .= intval($pl['opt']['border'][0]) . ' ';
								$annots .= intval($pl['opt']['border'][1]) . ' ';
								$annots .= intval($pl['opt']['border'][2]);
								if (isset($pl['opt']['border'][3]) && is_array($pl['opt']['border'][3])) {
									$annots .= ' [';

									foreach ($pl['opt']['border'][3] as $dash) {
										$annots .= intval($dash) . ' ';
									}

									$annots .= ']';
								}
							}
							else {
								$annots .= '0 0 0';
							}

							$annots .= ']';
						}

						if (isset($pl['opt']['be']) && is_array($pl['opt']['be'])) {
							$annots .= ' /BE <<';
							$bstyles = array('S', 'C');
							if (isset($pl['opt']['be']['s']) && in_array($pl['opt']['be']['s'], $markups)) {
								$annots .= ' /S /' . $pl['opt']['bs']['s'];
							}
							else {
								$annots .= ' /S /S';
							}

							if (isset($pl['opt']['be']['i']) && (0 <= $pl['opt']['be']['i']) && ($pl['opt']['be']['i'] <= 2)) {
								$annots .= ' /I ' . sprintf(' %.4F', $pl['opt']['be']['i']);
							}

							$annots .= '>>';
						}

						if (isset($pl['opt']['c']) && is_array($pl['opt']['c']) && !empty($pl['opt']['c'])) {
							$annots .= ' /C [';

							foreach ($pl['opt']['c'] as $col) {
								$col = intval($col);
								$color = ($col <= 0 ? 0 : (255 <= $col ? 1 : $col / 255));
								$annots .= sprintf(' %.4F', $color);
							}

							$annots .= ']';
						}

						$markups = array('text', 'freetext', 'line', 'square', 'circle', 'polygon', 'polyline', 'highlight', 'underline', 'squiggly', 'strikeout', 'stamp', 'caret', 'ink', 'fileattachment', 'sound');

						if (in_array(strtolower($pl['opt']['subtype']), $markups)) {
							if (isset($pl['opt']['t']) && is_string($pl['opt']['t'])) {
								$annots .= ' /T ' . $this->_textstring($pl['opt']['t']);
							}

							if (isset($pl['opt']['ca'])) {
								$annots .= ' /CA ' . sprintf('%.4F', floatval($pl['opt']['ca']));
							}

							if (isset($pl['opt']['rc'])) {
								$annots .= ' /RC ' . $this->_textstring($pl['opt']['rc']);
							}

							$annots .= ' /CreationDate ' . $this->_datestring();

							if (isset($pl['opt']['subj'])) {
								$annots .= ' /Subj ' . $this->_textstring($pl['opt']['subj']);
							}
						}

						$lineendings = array('Square', 'Circle', 'Diamond', 'OpenArrow', 'ClosedArrow', 'None', 'Butt', 'ROpenArrow', 'RClosedArrow', 'Slash');

						switch (strtolower($pl['opt']['subtype'])) {
						case 'text':
							if (isset($pl['opt']['open'])) {
								$annots .= ' /Open ' . (strtolower($pl['opt']['open']) == 'true' ? 'true' : 'false');
							}

							$iconsapp = array('Comment', 'Help', 'Insert', 'Key', 'NewParagraph', 'Note', 'Paragraph');
							if (isset($pl['opt']['name']) && in_array($pl['opt']['name'], $iconsapp)) {
								$annots .= ' /Name /' . $pl['opt']['name'];
							}
							else {
								$annots .= ' /Name /Note';
							}

							$statemodels = array('Marked', 'Review');
							if (isset($pl['opt']['statemodel']) && in_array($pl['opt']['statemodel'], $statemodels)) {
								$annots .= ' /StateModel /' . $pl['opt']['statemodel'];
							}
							else {
								$pl['opt']['statemodel'] = 'Marked';
								$annots .= ' /StateModel /' . $pl['opt']['statemodel'];
							}

							if ($pl['opt']['statemodel'] == 'Marked') {
								$states = array('Accepted', 'Unmarked');
							}
							else {
								$states = array('Accepted', 'Rejected', 'Cancelled', 'Completed', 'None');
							}

							if (isset($pl['opt']['state']) && in_array($pl['opt']['state'], $states)) {
								$annots .= ' /State /' . $pl['opt']['state'];
							}
							else if ($pl['opt']['statemodel'] == 'Marked') {
								$annots .= ' /State /Unmarked';
							}
							else {
								$annots .= ' /State /None';
							}

							break;

						case 'link':
							if (is_string($pl['txt'])) {
								$annots .= ' /A <</S /URI /URI ' . $this->_datastring($this->unhtmlentities($pl['txt'])) . '>>';
							}
							else {
								$l = $this->links[$pl['txt']];
								$annots .= sprintf(' /Dest [%d 0 R /XYZ 0 %.2F null]', 1 + (2 * $l[0]), $this->pagedim[$l[0]]['h'] - ($l[1] * $this->k));
							}

							$hmodes = array('N', 'I', 'O', 'P');
							if (isset($pl['opt']['h']) && in_array($pl['opt']['h'], $hmodes)) {
								$annots .= ' /H /' . $pl['opt']['h'];
							}
							else {
								$annots .= ' /H /I';
							}

							break;

						case 'freetext':
							if (isset($pl['opt']['da']) && !empty($pl['opt']['da'])) {
								$annots .= ' /DA (' . $pl['opt']['da'] . ')';
							}

							if (isset($pl['opt']['q']) && (0 <= $pl['opt']['q']) && ($pl['opt']['q'] <= 2)) {
								$annots .= ' /Q ' . intval($pl['opt']['q']);
							}

							if (isset($pl['opt']['rc'])) {
								$annots .= ' /RC ' . $this->_textstring($pl['opt']['rc']);
							}

							if (isset($pl['opt']['ds'])) {
								$annots .= ' /DS ' . $this->_textstring($pl['opt']['ds']);
							}

							if (isset($pl['opt']['cl']) && is_array($pl['opt']['cl'])) {
								$annots .= ' /CL [';

								foreach ($pl['opt']['cl'] as $cl) {
									$annots .= sprintf('%.4F ', $cl * $this->k);
								}

								$annots .= ']';
							}

							$tfit = array('FreeText', 'FreeTextCallout', 'FreeTextTypeWriter');
							if (isset($pl['opt']['it']) && in_array($pl['opt']['it'], $tfit)) {
								$annots .= ' /IT ' . $pl['opt']['it'];
							}

							if (isset($pl['opt']['rd']) && is_array($pl['opt']['rd'])) {
								$l = $pl['opt']['rd'][0] * $this->k;
								$r = $pl['opt']['rd'][1] * $this->k;
								$t = $pl['opt']['rd'][2] * $this->k;
								$b = $pl['opt']['rd'][3] * $this->k;
								$annots .= ' /RD [' . sprintf('%.2F %.2F %.2F %.2F', $l, $r, $t, $b) . ']';
							}

							if (isset($pl['opt']['le']) && in_array($pl['opt']['le'], $lineendings)) {
								$annots .= ' /LE /' . $pl['opt']['le'];
							}

							break;

						case 'line':
							break;

						case 'square':
							break;

						case 'circle':
							break;

						case 'polygon':
							break;

						case 'polyline':
							break;

						case 'highlight':
							break;

						case 'underline':
							break;

						case 'squiggly':
							break;

						case 'strikeout':
							break;

						case 'stamp':
							break;

						case 'caret':
							break;

						case 'ink':
							break;

						case 'popup':
							break;

						case 'fileattachment':
							if (!isset($pl['opt']['fs'])) {
								break;
							}

							$filename = basename($pl['opt']['fs']);

							if (isset($this->embeddedfiles[$filename]['n'])) {
								$annots .= ' /FS <</Type /Filespec /F ' . $this->_datastring($filename) . ' /EF <</F ' . $this->embeddedfiles[$filename]['n'] . ' 0 R>> >>';
								$iconsapp = array('Graph', 'Paperclip', 'PushPin', 'Tag');
								if (isset($pl['opt']['name']) && in_array($pl['opt']['name'], $iconsapp)) {
									$annots .= ' /Name /' . $pl['opt']['name'];
								}
								else {
									$annots .= ' /Name /PushPin';
								}
							}

							break;

						case 'sound':
							if (!isset($pl['opt']['sound'])) {
								break;
							}

							$filename = basename($pl['opt']['sound']);

							if (isset($this->embeddedfiles[$filename]['n'])) {
								$annots .= ' /Sound <</Type /Sound';
								$iconsapp = array('Speaker', 'Mic');
								if (isset($pl['opt']['name']) && in_array($pl['opt']['name'], $iconsapp)) {
									$annots .= ' /Name /' . $pl['opt']['name'];
								}
								else {
									$annots .= ' /Name /Speaker';
								}
							}

							break;

						case 'movie':
							break;

						case 'widget':
							$hmode = array('N', 'I', 'O', 'P', 'T');
							if (isset($pl['opt']['h']) && in_array($pl['opt']['h'], $hmode)) {
								$annots .= ' /H /' . $pl['opt']['h'];
							}

							if (isset($pl['opt']['mk']) && is_array($pl['opt']['mk']) && !empty($pl['opt']['mk'])) {
								$annots .= ' /MK <<';

								if (isset($pl['opt']['mk']['r'])) {
									$annots .= ' /R ' . $pl['opt']['mk']['r'];
								}

								if (isset($pl['opt']['mk']['bc']) && is_array($pl['opt']['mk']['bc'])) {
									$annots .= ' /BC [';

									foreach ($pl['opt']['mk']['bc'] as $col) {
										$col = intval($col);
										$color = ($col <= 0 ? 0 : (255 <= $col ? 1 : $col / 255));
										$annots .= ' ' . $color;
									}

									$annots .= ']';
								}

								if (isset($pl['opt']['mk']['bg']) && is_array($pl['opt']['mk']['bg'])) {
									$annots .= ' /BG [';

									foreach ($pl['opt']['mk']['bg'] as $col) {
										$col = intval($col);
										$color = ($col <= 0 ? 0 : (255 <= $col ? 1 : $col / 255));
										$annots .= ' ' . $color;
									}

									$annots .= ']';
								}

								if (isset($pl['opt']['mk']['ca'])) {
									$annots .= ' /CA ' . $pl['opt']['mk']['ca'] . '';
								}

								if (isset($pl['opt']['mk']['rc'])) {
									$annots .= ' /RC ' . $pl['opt']['mk']['ca'] . '';
								}

								if (isset($pl['opt']['mk']['ac'])) {
									$annots .= ' /AC ' . $pl['opt']['mk']['ca'] . '';
								}

								if (isset($pl['opt']['mk']['i'])) {
									$info = $this->getImageBuffer($pl['opt']['mk']['i']);

									if ($info !== false) {
										$annots .= ' /I ' . $info['n'] . ' 0 R';
									}
								}

								if (isset($pl['opt']['mk']['ri'])) {
									$info = $this->getImageBuffer($pl['opt']['mk']['ri']);

									if ($info !== false) {
										$annots .= ' /RI ' . $info['n'] . ' 0 R';
									}
								}

								if (isset($pl['opt']['mk']['ix'])) {
									$info = $this->getImageBuffer($pl['opt']['mk']['ix']);

									if ($info !== false) {
										$annots .= ' /IX ' . $info['n'] . ' 0 R';
									}
								}

								if (isset($pl['opt']['mk']['if']) && is_array($pl['opt']['mk']['if']) && !empty($pl['opt']['mk']['if'])) {
									$annots .= ' /IF <<';
									$if_sw = array('A', 'B', 'S', 'N');
									if (isset($pl['opt']['mk']['if']['sw']) && in_array($pl['opt']['mk']['if']['sw'], $if_sw)) {
										$annots .= ' /SW /' . $pl['opt']['mk']['if']['sw'];
									}

									$if_s = array('A', 'P');
									if (isset($pl['opt']['mk']['if']['s']) && in_array($pl['opt']['mk']['if']['s'], $if_s)) {
										$annots .= ' /S /' . $pl['opt']['mk']['if']['s'];
									}

									if (isset($pl['opt']['mk']['if']['a']) && is_array($pl['opt']['mk']['if']['a']) && !empty($pl['opt']['mk']['if']['a'])) {
										$annots .= ' /A [' . $pl['opt']['mk']['if']['a'][0] . ' ' . $pl['opt']['mk']['if']['a'][1] . ']';
									}

									if (isset($pl['opt']['mk']['if']['fb']) && $pl['opt']['mk']['if']['fb']) {
										$annots .= ' /FB true';
									}

									$annots .= '>>';
								}

								if (isset($pl['opt']['mk']['tp']) && (0 <= $pl['opt']['mk']['tp']) && ($pl['opt']['mk']['tp'] <= 6)) {
									$annots .= ' /TP ' . $pl['opt']['mk']['tp'];
								}
								else {
									$annots .= ' /TP 0';
								}

								$annots .= '>>';
							}

							if (isset($this->radiobutton_groups[$n][$pl['txt']])) {
								$annots .= ' /Parent ' . $this->radiobutton_groups[$n][$pl['txt']] . ' 0 R';
							}

							if (isset($pl['opt']['t']) && is_string($pl['opt']['t'])) {
								$annots .= ' /T ' . $this->_datastring($pl['opt']['t']);
							}

							if (isset($pl['opt']['tu']) && is_string($pl['opt']['tu'])) {
								$annots .= ' /TU ' . $this->_datastring($pl['opt']['tu']);
							}

							if (isset($pl['opt']['tm']) && is_string($pl['opt']['tm'])) {
								$annots .= ' /TM ' . $this->_datastring($pl['opt']['tm']);
							}

							if (isset($pl['opt']['ff'])) {
								if (is_array($pl['opt']['ff'])) {
									$flag = 0;

									foreach ($pl['opt']['ff'] as $val) {
										$flag += 1 << ($val - 1);
									}
								}
								else {
									$flag = intval($pl['opt']['ff']);
								}

								$annots .= ' /Ff ' . $flag;
							}

							if (isset($pl['opt']['maxlen'])) {
								$annots .= ' /MaxLen ' . intval($pl['opt']['maxlen']);
							}

							if (isset($pl['opt']['v'])) {
								$annots .= ' /V';

								if (is_array($pl['opt']['v'])) {
									foreach ($pl['opt']['v'] as $optval) {
										$annots .= ' ' . $optval;
									}
								}
								else {
									$annots .= ' ' . $this->_textstring($pl['opt']['v']);
								}
							}

							if (isset($pl['opt']['dv']) && is_string($pl['opt']['dv'])) {
								$annots .= ' /DV';

								if (is_array($pl['opt']['dv'])) {
									foreach ($pl['opt']['dv'] as $optval) {
										$annots .= ' ' . $optval;
									}
								}
								else {
									$annots .= ' ' . $this->_textstring($pl['opt']['dv']);
								}
							}

							if (isset($pl['opt']['rv']) && is_string($pl['opt']['rv'])) {
								$annots .= ' /RV';

								if (is_array($pl['opt']['rv'])) {
									foreach ($pl['opt']['rv'] as $optval) {
										$annots .= ' ' . $optval;
									}
								}
								else {
									$annots .= ' ' . $this->_textstring($pl['opt']['rv']);
								}
							}

							if (isset($pl['opt']['a']) && !empty($pl['opt']['a'])) {
								$annots .= ' /A << ' . $pl['opt']['a'] . ' >>';
							}

							if (isset($pl['opt']['aa']) && !empty($pl['opt']['aa'])) {
								$annots .= ' /AA << ' . $pl['opt']['aa'] . ' >>';
							}

							if (isset($pl['opt']['da']) && !empty($pl['opt']['da'])) {
								$annots .= ' /DA (' . $pl['opt']['da'] . ')';
							}

							if (isset($pl['opt']['q']) && (0 <= $pl['opt']['q']) && ($pl['opt']['q'] <= 2)) {
								$annots .= ' /Q ' . intval($pl['opt']['q']);
							}

							if (isset($pl['opt']['opt']) && is_array($pl['opt']['opt']) && !empty($pl['opt']['opt'])) {
								$annots .= ' /Opt [';

								foreach ($pl['opt']['opt'] as $copt) {
									if (is_array($copt)) {
										$annots .= ' [' . $this->_textstring($copt[0]) . ' ' . $this->_textstring($copt[1]) . ']';
									}
									else {
										$annots .= ' ' . $this->_textstring($copt);
									}
								}

								$annots .= ']';
							}

							if (isset($pl['opt']['ti'])) {
								$annots .= ' /TI ' . intval($pl['opt']['ti']);
							}

							if (isset($pl['opt']['i']) && is_array($pl['opt']['i']) && !empty($pl['opt']['i'])) {
								$annots .= ' /I [';

								foreach ($pl['opt']['i'] as $copt) {
									$annots .= intval($copt) . ' ';
								}

								$annots .= ']';
							}

							break;

						case 'screen':
							break;

						case 'printermark':
							break;

						case 'trapnet':
							break;

						case 'watermark':
							break;

						case '3d':
							break;

						default:
							break;
						}

						$annots .= '>>';
						++$this->annot_obj_id;
						$this->offsets[$this->annot_obj_id] = $this->bufferlen;
						$this->_out($this->annot_obj_id . ' 0 obj');
						$this->_out($annots);
						$this->_out('endobj');
						if ($formfield && !isset($this->radiobutton_groups[$n][$pl['txt']])) {
							$this->form_obj_id[] = $this->annot_obj_id;
						}
					}
				}
			}
		}

		protected function _putAPXObject($w = 0, $h = 0, $stream = '')
		{
			$stream = trim($stream);
			++$this->apxo_obj_id;
			$this->offsets[$this->apxo_obj_id] = $this->bufferlen;
			$this->_out($this->apxo_obj_id . ' 0 obj');
			$this->_out('<<');
			$this->_out('/Type /XObject');
			$this->_out('/Subtype /Form');
			$this->_out('/FormType 1');

			if ($this->compress) {
				$stream = gzcompress($stream);
				$this->_out('/Filter /FlateDecode');
			}

			$rect = sprintf('%.2F %.2F', $w, $h);
			$this->_out('/BBox [0 0 ' . $rect . ']');
			$this->_out('/Matrix [1 0 0 1 0 0]');
			$this->_out('/Resources <</ProcSet [/PDF]>>');
			$this->_out('/Length ' . strlen($stream));
			$this->_out('>>');
			$this->_putstream($stream);
			$this->_out('endobj');
			return $this->apxo_obj_id;
		}

		protected function _putfonts()
		{
			$nf = $this->n;

			foreach ($this->diffs as $diff) {
				$this->_newobj();
				$this->_out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences [' . $diff . ']>>');
				$this->_out('endobj');
			}

			$mqr = $this->get_mqr();
			$this->set_mqr(false);

			foreach ($this->FontFiles as $file => $info) {
				$fontdir = $info['fontdir'];
				$file = strtolower($file);
				$fontfile = '';

				if (file_exists($fontdir . $file)) {
					$fontfile = $fontdir . $file;
				}
				else if (file_exists($this->_getfontpath() . $file)) {
					$fontfile = $this->_getfontpath() . $file;
				}
				else if (file_exists($file)) {
					$fontfile = $file;
				}

				if (!$this->empty_string($fontfile)) {
					$font = file_get_contents($fontfile);
					$compressed = substr($file, -2) == '.z';
					if (!$compressed && isset($info['length2'])) {
						$header = ord($font[0]) == 128;

						if ($header) {
							$font = substr($font, 6);
						}

						if ($header && (ord($font[$info['length1']]) == 128)) {
							$font = substr($font, 0, $info['length1']) . substr($font, $info['length1'] + 6);
						}
					}

					$this->_newobj();
					$this->FontFiles[$file]['n'] = $this->n;
					$this->_out('<</Length ' . strlen($font));

					if ($compressed) {
						$this->_out('/Filter /FlateDecode');
					}

					$this->_out('/Length1 ' . $info['length1']);

					if (isset($info['length2'])) {
						$this->_out('/Length2 ' . $info['length2'] . ' /Length3 0');
					}

					$this->_out('>>');
					$this->_putstream($font);
					$this->_out('endobj');
				}
			}

			$this->set_mqr($mqr);

			foreach ($this->fontkeys as $k) {
				$this->setFontSubBuffer($k, 'n', $this->n + 1);
				$font = $this->getFontBuffer($k);
				$type = $font['type'];
				$name = $font['name'];

				if ($type == 'core') {
					$obj_id = $this->_newobj();
					$this->_out('<</Type /Font');
					$this->_out('/Subtype /Type1');
					$this->_out('/BaseFont /' . $name);
					$this->_out('/Name /F' . $font['i']);
					if ((strtolower($name) != 'symbol') && (strtolower($name) != 'zapfdingbats')) {
						$this->_out('/Encoding /WinAnsiEncoding');
					}

					if (strtolower($name) == 'helvetica') {
						$this->annotation_fonts['helvetica'] = $k;
					}

					$this->_out('>>');
					$this->_out('endobj');
				}
				else {
					if (($type == 'Type1') || ($type == 'TrueType')) {
						$obj_id = $this->_newobj();
						$this->_out('<</Type /Font');
						$this->_out('/Subtype /' . $type);
						$this->_out('/BaseFont /' . $name);
						$this->_out('/Name /F' . $font['i']);
						$this->_out('/FirstChar 32 /LastChar 255');
						$this->_out('/Widths ' . ($this->n + 1) . ' 0 R');
						$this->_out('/FontDescriptor ' . ($this->n + 2) . ' 0 R');

						if ($font['enc']) {
							if (isset($font['diff'])) {
								$this->_out('/Encoding ' . ($nf + $font['diff']) . ' 0 R');
							}
							else {
								$this->_out('/Encoding /WinAnsiEncoding');
							}
						}

						$this->_out('>>');
						$this->_out('endobj');
						$this->_newobj();
						$cw = &$font['cw'];
						$s = '[';

						for ($i = 32; $i < 256; ++$i) {
							$s .= $cw[$i] . ' ';
						}

						$this->_out($s . ']');
						$this->_out('endobj');
						$this->_newobj();
						$s = '<</Type /FontDescriptor /FontName /' . $name;

						foreach ($font['desc'] as $fdk => $fdv) {
							$s .= ' /' . $fdk . ' ' . $fdv . '';
						}

						if (!$this->empty_string($font['file'])) {
							$s .= ' /FontFile' . ($type == 'Type1' ? '' : '2') . ' ' . $this->FontFiles[$font['file']]['n'] . ' 0 R';
						}

						$this->_out($s . '>>');
						$this->_out('endobj');
					}
					else {
						$mtd = '_put' . strtolower($type);

						if (!method_exists($this, $mtd)) {
							$this->Error('Unsupported font type: ' . $type);
						}

						$obj_id = $this->$mtd($font);
					}
				}

				$this->font_obj_ids[$k] = $obj_id;
			}
		}

		protected function _putfontwidths($font, $cidoffset = 0)
		{
			ksort($font['cw']);
			$rangeid = 0;
			$range = array();
			$prevcid = -2;
			$prevwidth = -1;
			$interval = false;

			foreach ($font['cw'] as $cid => $width) {
				$cid -= $cidoffset;

				if ($width != $font['dw']) {
					if ($cid == $prevcid + 1) {
						if ($width == $prevwidth) {
							if ($width == $range[$rangeid][0]) {
								$range[$rangeid][] = $width;
							}
							else {
								array_pop($range[$rangeid]);
								$rangeid = $prevcid;
								$range[$rangeid] = array();
								$range[$rangeid][] = $prevwidth;
								$range[$rangeid][] = $width;
							}

							$interval = true;
							$range[$rangeid]['interval'] = true;
						}
						else {
							if ($interval) {
								$rangeid = $cid;
								$range[$rangeid] = array();
								$range[$rangeid][] = $width;
							}
							else {
								$range[$rangeid][] = $width;
							}

							$interval = false;
						}
					}
					else {
						$rangeid = $cid;
						$range[$rangeid] = array();
						$range[$rangeid][] = $width;
						$interval = false;
					}

					$prevcid = $cid;
					$prevwidth = $width;
				}
			}

			$prevk = -1;
			$nextk = -1;
			$prevint = false;

			foreach ($range as $k => $ws) {
				$cws = count($ws);
				if (($k == $nextk) && !$prevint && (!isset($ws['interval']) || ($cws < 4))) {
					if (isset($range[$k]['interval'])) {
						unset($range[$k]['interval']);
					}

					$range[$prevk] = array_merge($range[$prevk], $range[$k]);
					unset($range[$k]);
				}
				else {
					$prevk = $k;
				}

				$nextk = $k + $cws;

				if (isset($ws['interval'])) {
					if (3 < $cws) {
						$prevint = true;
					}
					else {
						$prevint = false;
					}

					unset($range[$k]['interval']);
					--$nextk;
				}
				else {
					$prevint = false;
				}
			}

			$w = '';

			foreach ($range as $k => $ws) {
				if (count(array_count_values($ws)) == 1) {
					$w .= ' ' . $k . ' ' . (($k + count($ws)) - 1) . ' ' . $ws[0];
				}
				else {
					$w .= ' ' . $k . ' [ ' . implode(' ', $ws) . ' ]';
				}
			}

			$this->_out('/W [' . $w . ' ]');
		}

		protected function _puttruetypeunicode($font)
		{
			$obj_id = $this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/Subtype /Type0');
			$this->_out('/BaseFont /' . $font['name'] . '');
			$this->_out('/Name /F' . $font['i']);
			$this->_out('/Encoding /' . $font['enc']);
			$this->_out('/ToUnicode /Identity-H');
			$this->_out('/DescendantFonts [' . ($this->n + 1) . ' 0 R]');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/Subtype /CIDFontType2');
			$this->_out('/BaseFont /' . $font['name'] . '');
			$cidinfo = '/Registry ' . $this->_datastring($font['cidinfo']['Registry']);
			$cidinfo .= ' /Ordering ' . $this->_datastring($font['cidinfo']['Ordering']);
			$cidinfo .= ' /Supplement ' . $font['cidinfo']['Supplement'];
			$this->_out('/CIDSystemInfo <<' . $cidinfo . '>>');
			$this->_out('/FontDescriptor ' . ($this->n + 1) . ' 0 R');
			$this->_out('/DW ' . $font['dw'] . '');
			$this->_putfontwidths($font, 0);
			$this->_out('/CIDToGIDMap ' . ($this->n + 2) . ' 0 R');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Type /FontDescriptor');
			$this->_out('/FontName /' . $font['name']);

			foreach ($font['desc'] as $key => $value) {
				$this->_out('/' . $key . ' ' . $value);
			}

			$fontdir = '';

			if (!$this->empty_string($font['file'])) {
				$this->_out('/FontFile2 ' . $this->FontFiles[$font['file']]['n'] . ' 0 R');
				$fontdir = $this->FontFiles[$font['file']]['fontdir'];
			}

			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			if (isset($font['ctg']) && !$this->empty_string($font['ctg'])) {
				$ctgfile = strtolower($font['ctg']);
				$fontfile = '';

				if (file_exists($fontdir . $ctgfile)) {
					$fontfile = $fontdir . $ctgfile;
				}
				else if (file_exists($this->_getfontpath() . $ctgfile)) {
					$fontfile = $this->_getfontpath() . $ctgfile;
				}
				else if (file_exists($ctgfile)) {
					$fontfile = $ctgfile;
				}

				if ($this->empty_string($fontfile)) {
					$this->Error('Font file not found: ' . $ctgfile);
				}

				$size = filesize($fontfile);
				$this->_out('<</Length ' . $size . '');

				if (substr($fontfile, -2) == '.z') {
					$this->_out('/Filter /FlateDecode');
				}

				$this->_out('>>');
				$this->_putstream(file_get_contents($fontfile));
			}

			$this->_out('endobj');
			return $obj_id;
		}

		protected function _putcidfont0($font)
		{
			$cidoffset = 0;

			if (!isset($font['cw'][1])) {
				$cidoffset = 31;
			}

			if (isset($font['cidinfo']['uni2cid'])) {
				$uni2cid = $font['cidinfo']['uni2cid'];
				$cw = array();

				foreach ($font['cw'] as $uni => $width) {
					if (isset($uni2cid[$uni])) {
						$cw[$uni2cid[$uni] + $cidoffset] = $width;
					}
					else if ($uni < 256) {
						$cw[$uni] = $width;
					}
				}

				$font = array_merge($font, array('cw' => $cw));
			}

			$name = $font['name'];
			$enc = $font['enc'];

			if ($enc) {
				$longname = $name . '-' . $enc;
			}
			else {
				$longname = $name;
			}

			$obj_id = $this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/Subtype /Type0');
			$this->_out('/BaseFont /' . $longname);
			$this->_out('/Name /F' . $font['i']);

			if ($enc) {
				$this->_out('/Encoding /' . $enc);
			}

			$this->_out('/DescendantFonts [' . ($this->n + 1) . ' 0 R]');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/Subtype /CIDFontType0');
			$this->_out('/BaseFont /' . $name);
			$cidinfo = '/Registry ' . $this->_datastring($font['cidinfo']['Registry']);
			$cidinfo .= ' /Ordering ' . $this->_datastring($font['cidinfo']['Ordering']);
			$cidinfo .= ' /Supplement ' . $font['cidinfo']['Supplement'];
			$this->_out('/CIDSystemInfo <<' . $cidinfo . '>>');
			$this->_out('/FontDescriptor ' . ($this->n + 1) . ' 0 R');
			$this->_out('/DW ' . $font['dw']);
			$this->_putfontwidths($font, $cidoffset);
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$s = '<</Type /FontDescriptor /FontName /' . $name;

			foreach ($font['desc'] as $k => $v) {
				if ($k != 'Style') {
					$s .= ' /' . $k . ' ' . $v . '';
				}
			}

			$this->_out($s . '>>');
			$this->_out('endobj');
			return $obj_id;
		}

		protected function _putimages()
		{
			$filter = ($this->compress ? '/Filter /FlateDecode ' : '');

			foreach ($this->imagekeys as $file) {
				$info = $this->getImageBuffer($file);
				$this->_newobj();
				$this->setImageSubBuffer($file, 'n', $this->n);
				$this->_out('<</Type /XObject');
				$this->_out('/Subtype /Image');
				$this->_out('/Width ' . $info['w']);
				$this->_out('/Height ' . $info['h']);

				if (isset($info['masked'])) {
					$this->_out('/SMask ' . ($this->n - 1) . ' 0 R');
				}

				if ($info['cs'] == 'Indexed') {
					$this->_out('/ColorSpace [/Indexed /DeviceRGB ' . ((strlen($info['pal']) / 3) - 1) . ' ' . ($this->n + 1) . ' 0 R]');
				}
				else {
					$this->_out('/ColorSpace /' . $info['cs']);

					if ($info['cs'] == 'DeviceCMYK') {
						$this->_out('/Decode [1 0 1 0 1 0 1 0]');
					}
				}

				$this->_out('/BitsPerComponent ' . $info['bpc']);

				if (isset($info['f'])) {
					$this->_out('/Filter /' . $info['f']);
				}

				if (isset($info['parms'])) {
					$this->_out($info['parms']);
				}

				if (isset($info['trns']) && is_array($info['trns'])) {
					$trns = '';
					$count_info = count($info['trns']);

					for ($i = 0; $i < $count_info; ++$i) {
						$trns .= $info['trns'][$i] . ' ' . $info['trns'][$i] . ' ';
					}

					$this->_out('/Mask [' . $trns . ']');
				}

				$this->_out('/Length ' . strlen($info['data']) . '>>');
				$this->_putstream($info['data']);
				$this->_out('endobj');

				if ($info['cs'] == 'Indexed') {
					$this->_newobj();
					$pal = ($this->compress ? gzcompress($info['pal']) : $info['pal']);
					$this->_out('<<' . $filter . '/Length ' . strlen($pal) . '>>');
					$this->_putstream($pal);
					$this->_out('endobj');
				}
			}
		}

		protected function _putspotcolors()
		{
			foreach ($this->spot_colors as $name => $color) {
				$this->_newobj();
				$this->spot_colors[$name]['n'] = $this->n;
				$this->_out('[/Separation /' . str_replace(' ', '#20', $name));
				$this->_out('/DeviceCMYK <<');
				$this->_out('/Range [0 1 0 1 0 1 0 1] /C0 [0 0 0 0] ');
				$this->_out(sprintf('/C1 [%.4F %.4F %.4F %.4F] ', $color['c'] / 100, $color['m'] / 100, $color['y'] / 100, $color['k'] / 100));
				$this->_out('/FunctionType 2 /Domain [0 1] /N 1>>]');
				$this->_out('endobj');
			}
		}

		protected function _putxobjectdict()
		{
			foreach ($this->imagekeys as $file) {
				$info = $this->getImageBuffer($file);
				$this->_out('/I' . $info['i'] . ' ' . $info['n'] . ' 0 R');
			}
		}

		protected function _putresourcedict()
		{
			$this->_out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
			$this->_out('/Font <<');

			foreach ($this->fontkeys as $fontkey) {
				$font = $this->getFontBuffer($fontkey);
				$this->_out('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
			}

			$this->_out('>>');
			$this->_out('/XObject <<');
			$this->_putxobjectdict();
			$this->_out('>>');
			$this->_out('/Properties <</OC1 ' . $this->n_ocg_print . ' 0 R /OC2 ' . $this->n_ocg_view . ' 0 R>>');
			$this->_out('/ExtGState <<');

			foreach ($this->extgstates as $k => $extgstate) {
				$this->_out('/GS' . $k . ' ' . $extgstate['n'] . ' 0 R');
			}

			$this->_out('>>');
			if (isset($this->gradients) && (0 < count($this->gradients))) {
				$this->_out('/Shading <<');

				foreach ($this->gradients as $id => $grad) {
					$this->_out('/Sh' . $id . ' ' . $grad['id'] . ' 0 R');
				}

				$this->_out('>>');
			}

			if (isset($this->spot_colors) && (0 < count($this->spot_colors))) {
				$this->_out('/ColorSpace <<');

				foreach ($this->spot_colors as $color) {
					$this->_out('/CS' . $color['i'] . ' ' . $color['n'] . ' 0 R');
				}

				$this->_out('>>');
			}
		}

		protected function _putresources()
		{
			$this->_putextgstates();
			$this->_putocg();
			$this->_putfonts();
			$this->_putimages();
			$this->_putspotcolors();
			$this->_putshaders();
			$this->offsets[2] = $this->bufferlen;
			$this->_out('2 0 obj');
			$this->_out('<<');
			$this->_putresourcedict();
			$this->_out('>>');
			$this->_out('endobj');
			$this->_putbookmarks();
			$this->_putEmbeddedFiles();
			$this->_putannotsobjs();
			$this->_putjavascript();

			if ($this->encrypted) {
				$this->_newobj();
				$this->enc_obj_id = $this->n;
				$this->_out('<<');
				$this->_putencryption();
				$this->_out('>>');
				$this->_out('endobj');
			}
		}

		protected function _putinfo()
		{
			if ($this->empty_string($this->title)) {
				$this->title = '?';
			}

			$this->_out('/Title ' . $this->_textstring($this->title));

			if ($this->empty_string($this->author)) {
				$this->author = '?';
			}

			$this->_out('/Author ' . $this->_textstring($this->author));

			if ($this->empty_string($this->subject)) {
				$this->subject = '?';
			}

			$this->_out('/Subject ' . $this->_textstring($this->subject));

			if ($this->empty_string($this->keywords)) {
				$this->keywords = '?';
			}

			$this->_out('/Keywords ' . $this->_textstring($this->keywords));

			if ($this->empty_string($this->creator)) {
				$this->creator = '?';
			}

			$this->_out('/Creator ' . $this->_textstring($this->creator));

			if (defined('PDF_PRODUCER')) {
				$this->_out('/Producer ' . $this->_textstring(PDF_PRODUCER));
			}
			else {
				$this->_out('/Producer ' . $this->_textstring('TCPDF'));
			}

			$this->_out('/CreationDate ' . $this->_datestring());
			$this->_out('/ModDate ' . $this->_datestring());
		}

		protected function _putcatalog()
		{
			$this->_out('/Type /Catalog');
			$this->_out('/Pages 1 0 R');

			if ($this->ZoomMode == 'fullpage') {
				$this->_out('/OpenAction [3 0 R /Fit]');
			}
			else if ($this->ZoomMode == 'fullwidth') {
				$this->_out('/OpenAction [3 0 R /FitH null]');
			}
			else if ($this->ZoomMode == 'real') {
				$this->_out('/OpenAction [3 0 R /XYZ null null 1]');
			}
			else if (!is_string($this->ZoomMode)) {
				$this->_out('/OpenAction [3 0 R /XYZ null null ' . ($this->ZoomMode / 100) . ']');
			}

			if (isset($this->LayoutMode) && !$this->empty_string($this->LayoutMode)) {
				$this->_out('/PageLayout /' . $this->LayoutMode . '');
			}

			if (isset($this->PageMode) && !$this->empty_string($this->PageMode)) {
				$this->_out('/PageMode /' . $this->PageMode);
			}

			if (isset($this->l['a_meta_language'])) {
				$this->_out('/Lang /' . $this->l['a_meta_language']);
			}

			$this->_out('/Names <<');
			if (!empty($this->javascript) || !empty($this->js_objects)) {
				$this->_out('/JavaScript ' . $this->n_js . ' 0 R');
			}

			$this->_out('>>');

			if (0 < count($this->outlines)) {
				$this->_out('/Outlines ' . $this->OutlineRoot . ' 0 R');
				$this->_out('/PageMode /UseOutlines');
			}

			$this->_putviewerpreferences();
			$p = $this->n_ocg_print . ' 0 R';
			$v = $this->n_ocg_view . ' 0 R';
			$as = '<</Event /Print /OCGs [' . $p . ' ' . $v . '] /Category [/Print]>> <</Event /View /OCGs [' . $p . ' ' . $v . '] /Category [/View]>>';
			$this->_out('/OCProperties <</OCGs [' . $p . ' ' . $v . '] /D <</ON [' . $p . '] /OFF [' . $v . '] /AS [' . $as . ']>>>>');
			if (!empty($this->form_obj_id) || ($this->sign && isset($this->signature_data['cert_type']))) {
				$this->_out('/AcroForm<<');
				$objrefs = '';
				if ($this->sign && isset($this->signature_data['cert_type'])) {
					$objrefs .= $this->sig_obj_id . ' 0 R';
				}

				if (!empty($this->form_obj_id)) {
					foreach ($this->form_obj_id as $objid) {
						$objrefs .= ' ' . $objid . ' 0 R';
					}
				}

				$this->_out('/Fields [' . $objrefs . ']');
				$this->_out('/NeedAppearances ' . (empty($this->form_obj_id) ? 'false' : 'true'));
				if ($this->sign && isset($this->signature_data['cert_type'])) {
					$this->_out('/SigFlags 3');
				}

				if (isset($this->annotation_fonts) && !empty($this->annotation_fonts)) {
					$this->_out('/DR <<');
					$this->_out('/Font <<');

					foreach ($this->annotation_fonts as $font => $fontkey) {
						$this->_out('/F' . ($fontkey + 1) . ' ' . $this->font_obj_ids[$font] . ' 0 R');
					}

					$this->_out('>>');
					$this->_out('>>');
				}

				$this->_out('/DA (/F' . (array_search('helvetica', $this->fontkeys) + 1) . ' 0 Tf 0 g)');
				$this->_out('/Q ' . ($this->rtl ? '2' : '0'));
				$this->_out('>>');
				if ($this->sign && isset($this->signature_data['cert_type'])) {
					if (0 < $this->signature_data['cert_type']) {
						$this->_out('/Perms<</DocMDP ' . ($this->sig_obj_id + 1) . ' 0 R>>');
					}
					else {
						$this->_out('/Perms<</UR3 ' . ($this->sig_obj_id + 1) . ' 0 R>>');
					}
				}
			}
		}

		protected function _putviewerpreferences()
		{
			$this->_out('/ViewerPreferences<<');

			if ($this->rtl) {
				$this->_out('/Direction /R2L');
			}
			else {
				$this->_out('/Direction /L2R');
			}

			if (isset($this->viewer_preferences['HideToolbar']) && $this->viewer_preferences['HideToolbar']) {
				$this->_out('/HideToolbar true');
			}

			if (isset($this->viewer_preferences['HideMenubar']) && $this->viewer_preferences['HideMenubar']) {
				$this->_out('/HideMenubar true');
			}

			if (isset($this->viewer_preferences['HideWindowUI']) && $this->viewer_preferences['HideWindowUI']) {
				$this->_out('/HideWindowUI true');
			}

			if (isset($this->viewer_preferences['FitWindow']) && $this->viewer_preferences['FitWindow']) {
				$this->_out('/FitWindow true');
			}

			if (isset($this->viewer_preferences['CenterWindow']) && $this->viewer_preferences['CenterWindow']) {
				$this->_out('/CenterWindow true');
			}

			if (isset($this->viewer_preferences['DisplayDocTitle']) && $this->viewer_preferences['DisplayDocTitle']) {
				$this->_out('/DisplayDocTitle true');
			}

			if (isset($this->viewer_preferences['NonFullScreenPageMode'])) {
				$this->_out('/NonFullScreenPageMode /' . $this->viewer_preferences['NonFullScreenPageMode'] . '');
			}

			if (isset($this->viewer_preferences['ViewArea'])) {
				$this->_out('/ViewArea /' . $this->viewer_preferences['ViewArea']);
			}

			if (isset($this->viewer_preferences['ViewClip'])) {
				$this->_out('/ViewClip /' . $this->viewer_preferences['ViewClip']);
			}

			if (isset($this->viewer_preferences['PrintArea'])) {
				$this->_out('/PrintArea /' . $this->viewer_preferences['PrintArea']);
			}

			if (isset($this->viewer_preferences['PrintClip'])) {
				$this->_out('/PrintClip /' . $this->viewer_preferences['PrintClip']);
			}

			if (isset($this->viewer_preferences['PrintScaling'])) {
				$this->_out('/PrintScaling /' . $this->viewer_preferences['PrintScaling']);
			}

			if (isset($this->viewer_preferences['Duplex']) && !$this->empty_string($this->viewer_preferences['Duplex'])) {
				$this->_out('/Duplex /' . $this->viewer_preferences['Duplex']);
			}

			if (isset($this->viewer_preferences['PickTrayByPDFSize'])) {
				if ($this->viewer_preferences['PickTrayByPDFSize']) {
					$this->_out('/PickTrayByPDFSize true');
				}
				else {
					$this->_out('/PickTrayByPDFSize false');
				}
			}

			if (isset($this->viewer_preferences['PrintPageRange'])) {
				$PrintPageRangeNum = '';

				foreach ($this->viewer_preferences['PrintPageRange'] as $k => $v) {
					$PrintPageRangeNum .= ' ' . ($v - 1) . '';
				}

				$this->_out('/PrintPageRange [' . substr($PrintPageRangeNum, 1) . ']');
			}

			if (isset($this->viewer_preferences['NumCopies'])) {
				$this->_out('/NumCopies ' . intval($this->viewer_preferences['NumCopies']));
			}

			$this->_out('>>');
		}

		protected function _puttrailer()
		{
			$this->_out('/Size ' . ($this->n + 1));
			$this->_out('/Root ' . $this->n . ' 0 R');
			$this->_out('/Info ' . ($this->n - 1) . ' 0 R');

			if ($this->encrypted) {
				$this->_out('/Encrypt ' . $this->enc_obj_id . ' 0 R');
				$this->_out('/ID [()()]');
			}
		}

		protected function _putheader()
		{
			$this->_out('%PDF-' . $this->PDFVersion);
		}

		protected function _enddoc()
		{
			$this->state = 1;
			$this->_putheader();
			$this->_putpages();
			$this->_putresources();
			if ($this->sign && isset($this->signature_data['cert_type'])) {
				$this->sig_obj_id = $this->_newobj();
				$pdfdoc = $this->getBuffer();
				if (isset($this->diskcache) && $this->diskcache) {
					unlink($this->buffer);
				}

				unset($this->buffer);
				$signature_widget_ref = sprintf('%u 0 R', $this->sig_obj_id);
				$signature_widget_ref .= str_repeat(' ', strlen($this->sig_annot_ref) - strlen($signature_widget_ref));
				$pdfdoc = str_replace($this->sig_annot_ref, $signature_widget_ref, $pdfdoc);
				$this->diskcache = false;
				$this->buffer = &$pdfdoc;
				$this->bufferlen = strlen($pdfdoc);
				$this->_out('<<');
				$this->_out('/Type /Annot /Subtype /Widget /Rect [0 0 0 0]');
				$this->_out('/P 3 0 R');
				$this->_out('/FT /Sig');
				$this->_out('/T ' . $this->_textstring('Signature'));
				$this->_out('/Ff 0');
				$this->_out('/V ' . ($this->sig_obj_id + 1) . ' 0 R');
				$this->_out('>>');
				$this->_out('endobj');
				$this->_newobj();
				$this->_out('<<');
				$this->_putsignature();
				$this->_out('>>');
				$this->_out('endobj');
			}

			$this->_newobj();
			$this->_out('<<');
			$this->_putinfo();
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<<');
			$this->_putcatalog();
			$this->_out('>>');
			$this->_out('endobj');
			$o = $this->bufferlen;
			$this->_out('xref');
			$this->_out('0 ' . ($this->n + 1));
			$this->_out('0000000000 65535 f ');

			for ($i = 1; $i <= $this->n; ++$i) {
				$this->_out(sprintf('%010d 00000 n ', $this->offsets[$i]));
			}

			if (isset($this->embeddedfiles) && (0 < count($this->embeddedfiles))) {
				$this->_out($this->embedded_start_obj_id . ' ' . count($this->embeddedfiles));

				foreach ($this->embeddedfiles as $filename => $filedata) {
					$this->_out(sprintf('%010d 00000 n ', $this->offsets[$filedata['n']]));
				}
			}

			if ($this->annots_start_obj_id < $this->annot_obj_id) {
				$this->_out(($this->annots_start_obj_id + 1) . ' ' . ($this->annot_obj_id - $this->annots_start_obj_id));

				for ($i = $this->annots_start_obj_id + 1; $i <= $this->annot_obj_id; ++$i) {
					$this->_out(sprintf('%010d 00000 n ', $this->offsets[$i]));
				}
			}

			if ($this->js_start_obj_id < $this->js_obj_id) {
				$this->_out(($this->js_start_obj_id + 1) . ' ' . ($this->js_obj_id - $this->js_start_obj_id));

				for ($i = $this->js_start_obj_id + 1; $i <= $this->js_obj_id; ++$i) {
					$this->_out(sprintf('%010d 00000 n ', $this->offsets[$i]));
				}
			}

			if ($this->apxo_start_obj_id < $this->apxo_obj_id) {
				$this->_out(($this->apxo_start_obj_id + 1) . ' ' . ($this->apxo_obj_id - $this->apxo_start_obj_id));

				for ($i = $this->apxo_start_obj_id + 1; $i <= $this->apxo_obj_id; ++$i) {
					$this->_out(sprintf('%010d 00000 n ', $this->offsets[$i]));
				}
			}

			$this->_out('trailer');
			$this->_out('<<');
			$this->_puttrailer();
			$this->_out('>>');
			$this->_out('startxref');
			$this->_out($o);
			$this->_out('%%EOF');
			$this->state = 3;

			if ($this->diskcache) {
				foreach ($this->imagekeys as $key) {
					unlink($this->images[$key]);
				}

				foreach ($this->fontkeys as $key) {
					unlink($this->fonts[$key]);
				}
			}
		}

		protected function _beginpage($orientation = '', $format = '')
		{
			++$this->page;
			$this->setPageBuffer($this->page, '');
			$this->transfmrk[$this->page] = array();
			$this->state = 2;

			if ($this->empty_string($orientation)) {
				if (isset($this->CurOrientation)) {
					$orientation = $this->CurOrientation;
				}
				else {
					$orientation = 'P';
				}
			}

			if ($this->empty_string($format)) {
				$this->setPageOrientation($orientation);
			}
			else {
				$this->setPageFormat($format, $orientation);
			}

			if ($this->rtl) {
				$this->x = $this->w - $this->rMargin;
			}
			else {
				$this->x = $this->lMargin;
			}

			$this->y = $this->tMargin;

			if (isset($this->newpagegroup[$this->page])) {
				$n = sizeof($this->pagegroups) + 1;
				$alias = '{nb' . $n . '}';
				$this->pagegroups[$alias] = 1;
				$this->currpagegroup = $alias;
			}
			else if ($this->currpagegroup) {
				++$this->pagegroups[$this->currpagegroup];
			}
		}

		protected function _endpage()
		{
			$this->setVisibility('all');
			$this->state = 1;
		}

		protected function _newobj()
		{
			++$this->n;
			$this->offsets[$this->n] = $this->bufferlen;
			$this->_out($this->n . ' 0 obj');
			return $this->n;
		}

		protected function _dounderline($x, $y, $txt)
		{
			$w = $this->GetStringWidth($txt);
			return $this->_dounderlinew($x, $y, $w);
		}

		protected function _dolinethrough($x, $y, $txt)
		{
			$w = $this->GetStringWidth($txt);
			return $this->_dolinethroughw($x, $y, $w);
		}

		protected function _dounderlinew($x, $y, $w)
		{
			$up = $this->CurrentFont['up'];
			$ut = $this->CurrentFont['ut'];
			return sprintf('%.2F %.2F %.2F %.2F re f', $x * $this->k, ($this->h - $y - (($up / 1000) * $this->FontSize)) * $this->k, $w * $this->k, ((0 - $ut) / 1000) * $this->FontSizePt);
		}

		protected function _dolinethroughw($x, $y, $w)
		{
			$up = $this->CurrentFont['up'];
			$ut = $this->CurrentFont['ut'];
			return sprintf('%.2F %.2F %.2F %.2F re f', $x * $this->k, ($this->h - $y - ($this->FontSize / 2) - (($up / 1000) * $this->FontSize)) * $this->k, $w * $this->k, ((0 - $ut) / 1000) * $this->FontSizePt);
		}

		protected function _freadint($f)
		{
			$a = unpack('Ni', fread($f, 4));
			return $a['i'];
		}

		protected function _escape($s)
		{
			return strtr($s, array(')' => '\\)', '(' => '\\(', '\\' => '\\\\', chr(13) => '\\r'));
		}

		protected function _datastring($s)
		{
			if ($this->encrypted) {
				$s = $this->_RC4($this->_objectkey($this->n), $s);
			}

			return '(' . $this->_escape($s) . ')';
		}

		protected function _datestring()
		{
			$current_time = substr_replace(date('YmdHisO'), '\'', 0 - 2, 0) . '\'';
			return $this->_datastring('D:' . $current_time);
		}

		protected function _textstring($s)
		{
			if ($this->isunicode) {
				$s = $this->UTF8ToUTF16BE($s, true);
			}

			return $this->_datastring($s);
		}

		protected function _escapetext($s)
		{
			if ($this->isunicode) {
				if (($this->CurrentFont['type'] == 'core') || ($this->CurrentFont['type'] == 'TrueType') || ($this->CurrentFont['type'] == 'Type1')) {
					$s = $this->UTF8ToLatin1($s);
				}
				else {
					$s = $this->utf8StrRev($s, false, $this->tmprtl);
				}
			}

			return $this->_escape($s);
		}

		protected function _putstream($s)
		{
			if ($this->encrypted) {
				$s = $this->_RC4($this->_objectkey($this->n), $s);
			}

			$this->_out('stream');
			$this->_out($s);
			$this->_out('endstream');
		}

		protected function _out($s)
		{
			if ($this->state == 2) {
				if (!$this->InFooter && isset($this->footerlen[$this->page]) && (0 < $this->footerlen[$this->page])) {
					$pagebuff = $this->getPageBuffer($this->page);
					$page = substr($pagebuff, 0, 0 - $this->footerlen[$this->page]);
					$footer = substr($pagebuff, 0 - $this->footerlen[$this->page]);
					$this->setPageBuffer($this->page, $page . $s . "\n" . $footer);
					$this->footerpos[$this->page] += strlen($s . "\n");
				}
				else {
					$this->setPageBuffer($this->page, $s . "\n", true);
				}
			}
			else {
				$this->setBuffer($s . "\n");
			}
		}

		protected function UTF8StringToArray($str)
		{
			if (isset($this->cache_UTF8StringToArray['_' . $str])) {
				return $this->cache_UTF8StringToArray['_' . $str];
			}

			if ($this->cache_maxsize_UTF8StringToArray <= $this->cache_size_UTF8StringToArray) {
				array_shift($this->cache_UTF8StringToArray);
			}

			++$this->cache_size_UTF8StringToArray;

			if (!$this->isunicode) {
				$strarr = array();
				$strlen = strlen($str);

				for ($i = 0; $i < $strlen; ++$i) {
					$strarr[] = ord($str[$i]);
				}

				$this->cache_UTF8StringToArray['_' . $str] = $strarr;
				return $strarr;
			}

			$unicode = array();
			$bytes = array();
			$numbytes = 1;
			$str .= '';
			$length = strlen($str);

			for ($i = 0; $i < $length; ++$i) {
				$char = ord($str[$i]);

				if (count($bytes) == 0) {
					if ($char <= 127) {
						$unicode[] = $char;
						$numbytes = 1;
					}
					else if (($char >> 5) == 6) {
						$bytes[] = ($char - 192) << 6;
						$numbytes = 2;
					}
					else if (($char >> 4) == 14) {
						$bytes[] = ($char - 224) << 12;
						$numbytes = 3;
					}
					else if (($char >> 3) == 30) {
						$bytes[] = ($char - 240) << 18;
						$numbytes = 4;
					}
					else {
						$unicode[] = 65533;
						$bytes = array();
						$numbytes = 1;
					}
				}
				else if (($char >> 6) == 2) {
					$bytes[] = $char - 128;

					if (count($bytes) == $numbytes) {
						$char = $bytes[0];

						for ($j = 1; $j < $numbytes; ++$j) {
							$char += $bytes[$j] << (($numbytes - $j - 1) * 6);
						}

						if (((55296 <= $char) && ($char <= 57343)) || (1114111 <= $char)) {
							$unicode[] = 65533;
						}
						else {
							$unicode[] = $char;
						}

						$bytes = array();
						$numbytes = 1;
					}
				}
				else {
					$unicode[] = 65533;
					$bytes = array();
					$numbytes = 1;
				}
			}

			$this->cache_UTF8StringToArray['_' . $str] = $unicode;
			return $unicode;
		}

		protected function UTF8ToUTF16BE($str, $setbom = true)
		{
			if (!$this->isunicode) {
				return $str;
			}

			$unicode = $this->UTF8StringToArray($str);
			return $this->arrUTF8ToUTF16BE($unicode, $setbom);
		}

		protected function UTF8ToLatin1($str)
		{
			global $utf8tolatin;

			if (!$this->isunicode) {
				return $str;
			}

			$outstr = '';
			$unicode = $this->UTF8StringToArray($str);

			foreach ($unicode as $char) {
				if ($char < 256) {
					$outstr .= chr($char);
				}
				else if (array_key_exists($char, $utf8tolatin)) {
					$outstr .= chr($utf8tolatin[$char]);
				}
				else if ($char == 65533) {
				}
				else {
					$outstr .= '?';
				}
			}

			return $outstr;
		}

		protected function arrUTF8ToUTF16BE($unicode, $setbom = true)
		{
			$outstr = '';

			if ($setbom) {
				$outstr .= '?';
			}

			foreach ($unicode as $char) {
				if ($char == 65533) {
					$outstr .= 'ÿ?;
				}
				else if ($char < 65536) {
					$outstr .= chr($char >> 8);
					$outstr .= chr($char & 255);
				}
				else {
					$char -= 65536;
					$w1 = 55296 | ($char >> 16);
					$w2 = 56320 | ($char & 1023);
					$outstr .= chr($w1 >> 8);
					$outstr .= chr($w1 & 255);
					$outstr .= chr($w2 >> 8);
					$outstr .= chr($w2 & 255);
				}
			}

			return $outstr;
		}

		public function setHeaderFont($font)
		{
			$this->header_font = $font;
		}

		public function getHeaderFont()
		{
			return $this->header_font;
		}

		public function setFooterFont($font)
		{
			$this->footer_font = $font;
		}

		public function getFooterFont()
		{
			return $this->footer_font;
		}

		public function setLanguageArray($language)
		{
			$this->l = $language;

			if (isset($this->l['a_meta_dir'])) {
				$this->rtl = $this->l['a_meta_dir'] == 'rtl' ? true : false;
			}
			else {
				$this->rtl = false;
			}
		}

		public function getPDFData()
		{
			if ($this->state < 3) {
				$this->Close();
			}

			return $this->buffer;
		}

		public function addHtmlLink($url, $name, $fill = 0, $firstline = false, $color = '', $style = -1)
		{
			if (!$this->empty_string($url) && ($url[0] == '#')) {
				$page = intval(substr($url, 1));
				$url = $this->AddLink();
				$this->SetLink($url, 0, $page);
			}

			$prevcolor = $this->fgcolor;
			$prevstyle = $this->FontStyle;

			if (empty($color)) {
				$this->SetTextColorArray($this->htmlLinkColorArray);
			}
			else {
				$this->SetTextColorArray($color);
			}

			if ($style == -1) {
				$this->SetFont('', $this->FontStyle . $this->htmlLinkFontStyle);
			}
			else {
				$this->SetFont('', $this->FontStyle . $style);
			}

			$ret = $this->Write($this->lasth, $name, $url, $fill, '', false, 0, $firstline);
			$this->SetFont('', $prevstyle);
			$this->SetTextColorArray($prevcolor);
			return $ret;
		}

		public function convertHTMLColorToDec($color = '#FFFFFF')
		{
			global $webcolor;
			$returncolor = false;
			$color = preg_replace('/[\\s]*/', '', $color);
			$color = strtolower($color);

			if (($dotpos = strpos($color, '.')) !== false) {
				$color = substr($color, $dotpos + 1);
			}

			if (strlen($color) == 0) {
				return false;
			}

			if (substr($color, 0, 3) == 'rgb') {
				$codes = substr($color, 4);
				$codes = str_replace(')', '', $codes);
				$returncolor = explode(',', $codes, 3);
				return $returncolor;
			}

			if (substr($color, 0, 1) != '#') {
				if (isset($webcolor[$color])) {
					$color_code = $webcolor[$color];
				}
				else {
					return false;
				}
			}
			else {
				$color_code = substr($color, 1);
			}

			switch (strlen($color_code)) {
			case 3:
				$r = substr($color_code, 0, 1);
				$g = substr($color_code, 1, 1);
				$b = substr($color_code, 2, 1);
				$returncolor['R'] = hexdec($r . $r);
				$returncolor['G'] = hexdec($g . $g);
				$returncolor['B'] = hexdec($b . $b);
				break;

			case 6:
				$returncolor['R'] = hexdec(substr($color_code, 0, 2));
				$returncolor['G'] = hexdec(substr($color_code, 2, 2));
				$returncolor['B'] = hexdec(substr($color_code, 4, 2));
				break;
			}

			return $returncolor;
		}

		public function pixelsToUnits($px)
		{
			return $px / ($this->imgscale * $this->k);
		}

		public function unhtmlentities($text_to_convert)
		{
			return html_entity_decode($text_to_convert, ENT_QUOTES, $this->encoding);
		}

		protected function _objectkey($n)
		{
			return substr($this->_md5_16($this->encryption_key . pack('VXxx', $n)), 0, 10);
		}

		protected function _putencryption()
		{
			$this->_out('/Filter /Standard');
			$this->_out('/V 1');
			$this->_out('/R 2');
			$this->_out('/O (' . $this->_escape($this->Ovalue) . ')');
			$this->_out('/U (' . $this->_escape($this->Uvalue) . ')');
			$this->_out('/P ' . $this->Pvalue);
		}

		protected function _RC4($key, $text)
		{
			if ($this->last_rc4_key != $key) {
				$k = str_repeat($key, (256 / strlen($key)) + 1);
				$rc4 = range(0, 255);
				$j = 0;

				for ($i = 0; $i < 256; ++$i) {
					$t = $rc4[$i];
					$j = ($j + $t + ord($k[$i])) % 256;
					$rc4[$i] = $rc4[$j];
					$rc4[$j] = $t;
				}

				$this->last_rc4_key = $key;
				$this->last_rc4_key_c = $rc4;
			}
			else {
				$rc4 = $this->last_rc4_key_c;
			}

			$len = strlen($text);
			$a = 0;
			$b = 0;
			$out = '';

			for ($i = 0; $i < $len; ++$i) {
				$a = ($a + 1) % 256;
				$t = $rc4[$a];
				$b = ($b + $t) % 256;
				$rc4[$a] = $rc4[$b];
				$rc4[$b] = $t;
				$k = $rc4[($rc4[$a] + $rc4[$b]) % 256];
				$out .= chr(ord($text[$i]) ^ $k);
			}

			return $out;
		}

		protected function _md5_16($str)
		{
			return pack('H*', md5($str));
		}

		protected function _Ovalue($user_pass, $owner_pass)
		{
			$tmp = $this->_md5_16($owner_pass);
			$owner_RC4_key = substr($tmp, 0, 5);
			return $this->_RC4($owner_RC4_key, $user_pass);
		}

		protected function _Uvalue()
		{
			return $this->_RC4($this->encryption_key, $this->padding);
		}

		protected function _generateencryptionkey($user_pass, $owner_pass, $protection)
		{
			$user_pass = substr($user_pass . $this->padding, 0, 32);
			$owner_pass = substr($owner_pass . $this->padding, 0, 32);
			$this->Ovalue = $this->_Ovalue($user_pass, $owner_pass);
			$tmp = $this->_md5_16($user_pass . $this->Ovalue . chr($protection) . 'ÿÿÿ');
			$this->encryption_key = substr($tmp, 0, 5);
			$this->Uvalue = $this->_Uvalue();
			$this->Pvalue = 0 - (($protection ^ 255) + 1);
		}

		public function SetProtection($permissions = array(), $user_pass = '', $owner_pass = NULL)
		{
			$options = array('print' => 4, 'modify' => 8, 'copy' => 16, 'annot-forms' => 32);
			$protection = 192;

			foreach ($permissions as $permission) {
				if (!isset($options[$permission])) {
					$this->Error('Incorrect permission: ' . $permission);
				}

				$protection += $options[$permission];
			}

			if ($owner_pass === NULL) {
				$owner_pass = uniqid(rand());
			}

			$this->encrypted = true;
			$this->_generateencryptionkey($user_pass, $owner_pass, $protection);
		}

		public function StartTransform()
		{
			$this->_out('q');
			$this->transfmrk[$this->page][] = $this->pagelen[$this->page];
			++$this->transfmatrix_key;
			$this->transfmatrix[$this->transfmatrix_key] = array();
		}

		public function StopTransform()
		{
			$this->_out('Q');

			if (isset($this->transfmatrix[$this->transfmatrix_key])) {
				array_pop($this->transfmatrix[$this->transfmatrix_key]);
				--$this->transfmatrix_key;
			}

			array_pop($this->transfmrk[$this->page]);
		}

		public function ScaleX($s_x, $x = '', $y = '')
		{
			$this->Scale($s_x, 100, $x, $y);
		}

		public function ScaleY($s_y, $x = '', $y = '')
		{
			$this->Scale(100, $s_y, $x, $y);
		}

		public function ScaleXY($s, $x = '', $y = '')
		{
			$this->Scale($s, $s, $x, $y);
		}

		public function Scale($s_x, $s_y, $x = '', $y = '')
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($this->rtl) {
				$x = $this->w - $x;
			}

			if (($s_x == 0) || ($s_y == 0)) {
				$this->Error('Please do not use values equal to zero for scaling');
			}

			$y = ($this->h - $y) * $this->k;
			$x *= $this->k;
			$s_x /= 100;
			$s_y /= 100;
			$tm[0] = $s_x;
			$tm[1] = 0;
			$tm[2] = 0;
			$tm[3] = $s_y;
			$tm[4] = $x * (1 - $s_x);
			$tm[5] = $y * (1 - $s_y);
			$this->Transform($tm);
		}

		public function MirrorH($x = '')
		{
			$this->Scale(-100, 100, $x);
		}

		public function MirrorV($y = '')
		{
			$this->Scale(100, -100, '', $y);
		}

		public function MirrorP($x = '', $y = '')
		{
			$this->Scale(-100, -100, $x, $y);
		}

		public function MirrorL($angle = 0, $x = '', $y = '')
		{
			$this->Scale(-100, 100, $x, $y);
			$this->Rotate(-2 * ($angle - 90), $x, $y);
		}

		public function TranslateX($t_x)
		{
			$this->Translate($t_x, 0);
		}

		public function TranslateY($t_y)
		{
			$this->Translate(0, $t_y);
		}

		public function Translate($t_x, $t_y)
		{
			if ($this->rtl) {
				$t_x = 0 - $t_x;
			}

			$tm[0] = 1;
			$tm[1] = 0;
			$tm[2] = 0;
			$tm[3] = 1;
			$tm[4] = $t_x * $this->k;
			$tm[5] = (0 - $t_y) * $this->k;
			$this->Transform($tm);
		}

		public function Rotate($angle, $x = '', $y = '')
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($this->rtl) {
				$x = $this->w - $x;
				$angle = 0 - $angle;
			}

			$y = ($this->h - $y) * $this->k;
			$x *= $this->k;
			$tm[0] = cos(deg2rad($angle));
			$tm[1] = sin(deg2rad($angle));
			$tm[2] = 0 - $tm[1];
			$tm[3] = $tm[0];
			$tm[4] = ($x + ($tm[1] * $y)) - ($tm[0] * $x);
			$tm[5] = $y - ($tm[0] * $y) - ($tm[1] * $x);
			$this->Transform($tm);
		}

		public function SkewX($angle_x, $x = '', $y = '')
		{
			$this->Skew($angle_x, 0, $x, $y);
		}

		public function SkewY($angle_y, $x = '', $y = '')
		{
			$this->Skew(0, $angle_y, $x, $y);
		}

		public function Skew($angle_x, $angle_y, $x = '', $y = '')
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($this->rtl) {
				$x = $this->w - $x;
				$angle_x = 0 - $angle_x;
			}

			if (($angle_x <= -90) || (90 <= $angle_x) || ($angle_y <= -90) || (90 <= $angle_y)) {
				$this->Error('Please use values between -90 and +90 degrees for Skewing.');
			}

			$x *= $this->k;
			$y = ($this->h - $y) * $this->k;
			$tm[0] = 1;
			$tm[1] = tan(deg2rad($angle_y));
			$tm[2] = tan(deg2rad($angle_x));
			$tm[3] = 1;
			$tm[4] = (0 - $tm[2]) * $y;
			$tm[5] = (0 - $tm[1]) * $x;
			$this->Transform($tm);
		}

		protected function Transform($tm)
		{
			$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $tm[0], $tm[1], $tm[2], $tm[3], $tm[4], $tm[5]));
			$this->transfmatrix[$this->transfmatrix_key][] = array('a' => $tm[0], 'b' => $tm[1], 'c' => $tm[2], 'd' => $tm[3], 'e' => $tm[4], 'f' => $tm[5]);

			if (end($this->transfmrk[$this->page]) !== false) {
				$key = key($this->transfmrk[$this->page]);
				$this->transfmrk[$this->page][$key] = $this->pagelen[$this->page];
			}
		}

		public function SetLineWidth($width)
		{
			$this->LineWidth = $width;
			$this->linestyleWidth = sprintf('%.2F w', $width * $this->k);

			if (0 < $this->page) {
				$this->_out($this->linestyleWidth);
			}
		}

		public function GetLineWidth()
		{
			return $this->LineWidth;
		}

		public function SetLineStyle($style)
		{
			if (!is_array($style)) {
				return NULL;
			}

			extract($style);

			if (isset($width)) {
				$width_prev = $this->LineWidth;
				$this->SetLineWidth($width);
				$this->LineWidth = $width_prev;
			}

			if (isset($cap)) {
				$ca = array('butt' => 0, 'round' => 1, 'square' => 2);

				if (isset($ca[$cap])) {
					$this->linestyleCap = $ca[$cap] . ' J';
					$this->_out($this->linestyleCap);
				}
			}

			if (isset($join)) {
				$ja = array('miter' => 0, 'round' => 1, 'bevel' => 2);

				if (isset($ja[$join])) {
					$this->linestyleJoin = $ja[$join] . ' j';
					$this->_out($this->linestyleJoin);
				}
			}

			if (isset($dash)) {
				$dash_string = '';

				if ($dash) {
					if (0 < preg_match('/^.+,/', $dash)) {
						$tab = explode(',', $dash);
					}
					else {
						$tab = array($dash);
					}

					$dash_string = '';

					foreach ($tab as $i => $v) {
						if ($i) {
							$dash_string .= ' ';
						}

						$dash_string .= sprintf('%.2F', $v);
					}
				}

				if (!isset($phase) || !$dash) {
					$phase = 0;
				}

				$this->linestyleDash = sprintf('[%s] %.2F d', $dash_string, $phase);
				$this->_out($this->linestyleDash);
			}

			if (isset($color)) {
				$this->SetDrawColorArray($color);
			}
		}

		protected function _outPoint($x, $y)
		{
			if ($this->rtl) {
				$x = $this->w - $x;
			}

			$this->_out(sprintf('%.2F %.2F m', $x * $this->k, ($this->h - $y) * $this->k));
		}

		protected function _outLine($x, $y)
		{
			if ($this->rtl) {
				$x = $this->w - $x;
			}

			$this->_out(sprintf('%.2F %.2F l', $x * $this->k, ($this->h - $y) * $this->k));
		}

		protected function _outRect($x, $y, $w, $h, $op)
		{
			if ($this->rtl) {
				$x = $this->w - $x - $w;
			}

			$this->_out(sprintf('%.2F %.2F %.2F %.2F re %s', $x * $this->k, ($this->h - $y) * $this->k, $w * $this->k, (0 - $h) * $this->k, $op));
		}

		protected function _outCurve($x1, $y1, $x2, $y2, $x3, $y3)
		{
			if ($this->rtl) {
				$x1 = $this->w - $x1;
				$x2 = $this->w - $x2;
				$x3 = $this->w - $x3;
			}

			$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($this->h - $y1) * $this->k, $x2 * $this->k, ($this->h - $y2) * $this->k, $x3 * $this->k, ($this->h - $y3) * $this->k));
		}

		public function Line($x1, $y1, $x2, $y2, $style = array())
		{
			if (is_array($style)) {
				$this->SetLineStyle($style);
			}

			$this->_outPoint($x1, $y1);
			$this->_outLine($x2, $y2);
			$this->_out(' S');
		}

		public function Rect($x, $y, $w, $h, $style = '', $border_style = array(), $fill_color = array())
		{
			if (!(false === strpos($style, 'F')) && isset($fill_color)) {
				$this->SetFillColorArray($fill_color);
			}

			switch ($style) {
			case 'F':
				$op = 'f';
				$border_style = array();
				$this->_outRect($x, $y, $w, $h, $op);
				break;

			case 'DF':
			case 'FD':
				if (!$border_style || isset($border_style['all'])) {
					$op = 'B';

					if (isset($border_style['all'])) {
						$this->SetLineStyle($border_style['all']);
						$border_style = array();
					}
				}
				else {
					$op = 'f';
				}

				$this->_outRect($x, $y, $w, $h, $op);
				break;

			case 'CNZ':
				$op = 'W n';
				$this->_outRect($x, $y, $w, $h, $op);
				break;

			case 'CEO':
				$op = 'W* n';
				$this->_outRect($x, $y, $w, $h, $op);
				break;

			default:
				$op = 'S';
				if (!$border_style || isset($border_style['all'])) {
					if (isset($border_style['all']) && $border_style['all']) {
						$this->SetLineStyle($border_style['all']);
						$border_style = array();
					}

					$this->_outRect($x, $y, $w, $h, $op);
				}

				break;
			}

			if ($border_style) {
				$border_style2 = array();

				foreach ($border_style as $line => $value) {
					$lenght = strlen($line);

					for ($i = 0; $i < $lenght; ++$i) {
						$border_style2[$line[$i]] = $value;
					}
				}

				$border_style = $border_style2;
				if (isset($border_style['L']) && $border_style['L']) {
					$this->Line($x, $y, $x, $y + $h, $border_style['L']);
				}

				if (isset($border_style['T']) && $border_style['T']) {
					$this->Line($x, $y, $x + $w, $y, $border_style['T']);
				}

				if (isset($border_style['R']) && $border_style['R']) {
					$this->Line($x + $w, $y, $x + $w, $y + $h, $border_style['R']);
				}

				if (isset($border_style['B']) && $border_style['B']) {
					$this->Line($x, $y + $h, $x + $w, $y + $h, $border_style['B']);
				}
			}
		}

		public function Curve($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3, $style = '', $line_style = array(), $fill_color = array())
		{
			if (!(false === strpos($style, 'F')) && isset($fill_color)) {
				$this->SetFillColorArray($fill_color);
			}

			switch ($style) {
			case 'F':
				$op = 'f';
				$line_style = array();
				break;

			case 'FD':
			case 'DF':
				$op = 'B';
				break;

			case 'CNZ':
				$op = 'W n';
				break;

			case 'CEO':
				$op = 'W* n';
				break;

			default:
				$op = 'S';
				break;
			}

			if ($line_style) {
				$this->SetLineStyle($line_style);
			}

			$this->_outPoint($x0, $y0);
			$this->_outCurve($x1, $y1, $x2, $y2, $x3, $y3);
			$this->_out($op);
		}

		public function Polycurve($x0, $y0, $segments, $style = '', $line_style = array(), $fill_color = array())
		{
			if (!(false === strpos($style, 'F')) && isset($fill_color)) {
				$this->SetFillColorArray($fill_color);
			}

			switch ($style) {
			case 'F':
				$op = 'f';
				$line_style = array();
				break;

			case 'FD':
			case 'DF':
				$op = 'B';
				break;

			case 'CNZ':
				$op = 'W n';
				break;

			case 'CEO':
				$op = 'W* n';
				break;

			default:
				$op = 'S';
				break;
			}

			if ($line_style) {
				$this->SetLineStyle($line_style);
			}

			$this->_outPoint($x0, $y0);

			foreach ($segments as $segment) {
				list($x1, $y1, $x2, $y2, $x3, $y3) = $segment;
				$this->_outCurve($x1, $y1, $x2, $y2, $x3, $y3);
			}

			$this->_out($op);
		}

		public function Ellipse($x0, $y0, $rx, $ry = 0, $angle = 0, $astart = 0, $afinish = 360, $style = '', $line_style = array(), $fill_color = array(), $nc = 8)
		{
			if ($angle) {
				$this->StartTransform();
				$this->Rotate($angle, $x0, $y0);
				$this->Ellipse($x0, $y0, $rx, $ry, 0, $astart, $afinish, $style, $line_style, $fill_color, $nc);
				$this->StopTransform();
				return NULL;
			}

			if ($rx) {
				if (!(false === strpos($style, 'F')) && isset($fill_color)) {
					$this->SetFillColorArray($fill_color);
				}

				switch ($style) {
				case 'F':
					$op = 'f';
					$line_style = array();
					break;

				case 'FD':
				case 'DF':
					$op = 'B';
					break;

				case 'C':
					$op = 's';
					break;

				case 'CNZ':
					$op = 'W n';
					break;

				case 'CEO':
					$op = 'W* n';
					break;

				default:
					$op = 'S';
					break;
				}

				if ($line_style) {
					$this->SetLineStyle($line_style);
				}

				if (!$ry) {
					$ry = $rx;
				}

				$rx *= $this->k;
				$ry *= $this->k;

				if ($nc < 2) {
					$nc = 2;
				}

				$astart = deg2rad((double) $astart);
				$afinish = deg2rad((double) $afinish);
				$total_angle = $afinish - $astart;
				$dt = $total_angle / $nc;
				$dtm = $dt / 3;
				$x0 *= $this->k;
				$y0 = ($this->h - $y0) * $this->k;
				$t1 = $astart;
				$a0 = $x0 + ($rx * cos($t1));
				$b0 = $y0 + ($ry * sin($t1));
				$c0 = (0 - $rx) * sin($t1);
				$d0 = $ry * cos($t1);
				$this->_outPoint($a0 / $this->k, $this->h - ($b0 / $this->k));

				for ($i = 1; $i <= $nc; ++$i) {
					$t1 = ($i * $dt) + $astart;
					$a1 = $x0 + ($rx * cos($t1));
					$b1 = $y0 + ($ry * sin($t1));
					$c1 = (0 - $rx) * sin($t1);
					$d1 = $ry * cos($t1);
					$this->_outCurve(($a0 + ($c0 * $dtm)) / $this->k, $this->h - (($b0 + ($d0 * $dtm)) / $this->k), ($a1 - ($c1 * $dtm)) / $this->k, $this->h - (($b1 - ($d1 * $dtm)) / $this->k), $a1 / $this->k, $this->h - ($b1 / $this->k));
					$a0 = $a1;
					$b0 = $b1;
					$c0 = $c1;
					$d0 = $d1;
				}

				$this->_out($op);
			}
		}

		public function Circle($x0, $y0, $r, $astart = 0, $afinish = 360, $style = '', $line_style = array(), $fill_color = array(), $nc = 8)
		{
			$this->Ellipse($x0, $y0, $r, 0, 0, $astart, $afinish, $style, $line_style, $fill_color, $nc);
		}

		public function PolyLine($p, $style = '', $line_style = array(), $fill_color = array())
		{
			$this->Polygon($p, $style, $line_style, $fill_color, false);
		}

		public function Polygon($p, $style = '', $line_style = array(), $fill_color = array(), $closed = true)
		{
			$nc = count($p);
			$np = $nc / 2;

			if ($closed) {
				for ($i = 0; $i < 4; ++$i) {
					$p[$nc + $i] = $p[$i];
				}

				if (isset($line_style[0])) {
					$line_style[$np] = $line_style[0];
				}

				$nc += 4;
			}

			if (!(false === strpos($style, 'F')) && isset($fill_color)) {
				$this->SetFillColorArray($fill_color);
			}

			switch ($style) {
			case 'F':
				$line_style = array();
				$op = 'f';
				break;

			case 'FD':
			case 'DF':
				$op = 'B';
				break;

			case 'CNZ':
				$op = 'W n';
				break;

			case 'CEO':
				$op = 'W* n';
				break;

			default:
				$op = 'S';
				break;
			}

			$draw = true;

			if ($line_style) {
				if (isset($line_style['all'])) {
					$this->SetLineStyle($line_style['all']);
				}
				else {
					$draw = false;

					if ($op == 'B') {
						$op = 'f';
						$this->_outPoint($p[0], $p[1]);

						for ($i = 2; $i < $nc; $i = $i + 2) {
							$this->_outLine($p[$i], $p[$i + 1]);
						}

						$this->_out($op);
					}

					$this->_outPoint($p[0], $p[1]);

					for ($i = 2; $i < $nc; $i = $i + 2) {
						$line_num = ($i / 2) - 1;

						if (isset($line_style[$line_num])) {
							if ($line_style[$line_num] != 0) {
								if (is_array($line_style[$line_num])) {
									$this->_out('S');
									$this->SetLineStyle($line_style[$line_num]);
									$this->_outPoint($p[$i - 2], $p[$i - 1]);
									$this->_outLine($p[$i], $p[$i + 1]);
									$this->_out('S');
									$this->_outPoint($p[$i], $p[$i + 1]);
								}
								else {
									$this->_outLine($p[$i], $p[$i + 1]);
								}
							}
						}
						else {
							$this->_outLine($p[$i], $p[$i + 1]);
						}
					}

					$this->_out($op);
				}
			}

			if ($draw) {
				$this->_outPoint($p[0], $p[1]);

				for ($i = 2; $i < $nc; $i = $i + 2) {
					$this->_outLine($p[$i], $p[$i + 1]);
				}

				$this->_out($op);
			}
		}

		public function RegularPolygon($x0, $y0, $r, $ns, $angle = 0, $draw_circle = false, $style = '', $line_style = array(), $fill_color = array(), $circle_style = '', $circle_outLine_style = array(), $circle_fill_color = array())
		{
			if ($ns < 3) {
				$ns = 3;
			}

			if ($draw_circle) {
				$this->Circle($x0, $y0, $r, 0, 360, $circle_style, $circle_outLine_style, $circle_fill_color);
			}

			$p = array();

			for ($i = 0; $i < $ns; ++$i) {
				$a = $angle + (($i * 360) / $ns);
				$a_rad = deg2rad((double) $a);
				$p[] = $x0 + ($r * sin($a_rad));
				$p[] = $y0 + ($r * cos($a_rad));
			}

			$this->Polygon($p, $style, $line_style, $fill_color);
		}

		public function StarPolygon($x0, $y0, $r, $nv, $ng, $angle = 0, $draw_circle = false, $style = '', $line_style = array(), $fill_color = array(), $circle_style = '', $circle_outLine_style = array(), $circle_fill_color = array())
		{
			if ($nv < 2) {
				$nv = 2;
			}

			if ($draw_circle) {
				$this->Circle($x0, $y0, $r, 0, 360, $circle_style, $circle_outLine_style, $circle_fill_color);
			}

			$p2 = array();
			$visited = array();

			for ($i = 0; $i < $nv; ++$i) {
				$a = $angle + (($i * 360) / $nv);
				$a_rad = deg2rad((double) $a);
				$p2[] = $x0 + ($r * sin($a_rad));
				$p2[] = $y0 + ($r * cos($a_rad));
				$visited[] = false;
			}

			$p = array();
			$i = 0;

			do {
				$p[] = $p2[$i * 2];
				$p[] = $p2[($i * 2) + 1];
				$visited[$i] = true;
				$i += $ng;
				$i %= $nv;
			} while (!$visited[$i]);

			$this->Polygon($p, $style, $line_style, $fill_color);
		}

		public function RoundedRect($x, $y, $w, $h, $r, $round_corner = '1111', $style = '', $border_style = array(), $fill_color = array())
		{
			if ('0000' == $round_corner) {
				$this->Rect($x, $y, $w, $h, $style, $border_style, $fill_color);
			}
			else {
				if (!(false === strpos($style, 'F')) && isset($fill_color)) {
					$this->SetFillColorArray($fill_color);
				}

				switch ($style) {
				case 'F':
					$border_style = array();
					$op = 'f';
					break;

				case 'FD':
				case 'DF':
					$op = 'B';
					break;

				case 'CNZ':
					$op = 'W n';
					break;

				case 'CEO':
					$op = 'W* n';
					break;

				default:
					$op = 'S';
					break;
				}

				if ($border_style) {
					$this->SetLineStyle($border_style);
				}

				$MyArc = (4 / 3) * (sqrt(2) - 1);
				$this->_outPoint($x + $r, $y);
				$xc = ($x + $w) - $r;
				$yc = $y + $r;
				$this->_outLine($xc, $y);

				if ($round_corner[0]) {
					$this->_outCurve($xc + ($r * $MyArc), $yc - $r, $xc + $r, $yc - ($r * $MyArc), $xc + $r, $yc);
				}
				else {
					$this->_outLine($x + $w, $y);
				}

				$xc = ($x + $w) - $r;
				$yc = ($y + $h) - $r;
				$this->_outLine($x + $w, $yc);

				if ($round_corner[1]) {
					$this->_outCurve($xc + $r, $yc + ($r * $MyArc), $xc + ($r * $MyArc), $yc + $r, $xc, $yc + $r);
				}
				else {
					$this->_outLine($x + $w, $y + $h);
				}

				$xc = $x + $r;
				$yc = ($y + $h) - $r;
				$this->_outLine($xc, $y + $h);

				if ($round_corner[2]) {
					$this->_outCurve($xc - ($r * $MyArc), $yc + $r, $xc - $r, $yc + ($r * $MyArc), $xc - $r, $yc);
				}
				else {
					$this->_outLine($x, $y + $h);
				}

				$xc = $x + $r;
				$yc = $y + $r;
				$this->_outLine($x, $yc);

				if ($round_corner[3]) {
					$this->_outCurve($xc - $r, $yc - ($r * $MyArc), $xc - ($r * $MyArc), $yc - $r, $xc, $yc - $r);
				}
				else {
					$this->_outLine($x, $y);
					$this->_outLine($x + $r, $y);
				}

				$this->_out($op);
			}
		}

		public function Arrow($x0, $y0, $x1, $y1, $head_style = 0, $arm_size = 5, $arm_angle = 15)
		{
			$dir_angle = rad2deg(atan2($y0 - $y1, $x0 - $x1));
			$sx1 = $x1;
			$sy1 = $y1;

			if (0 < $head_style) {
				$sx1 = $x1 + (($arm_size - $this->LineWidth) * cos(deg2rad($dir_angle)));
				$sy1 = $y1 + (($arm_size - $this->LineWidth) * sin(deg2rad($dir_angle)));
			}

			$this->Line($x0, $y0, $sx1, $sy1);
			$x2L = $x1 + ($arm_size * cos(deg2rad($dir_angle + $arm_angle)));
			$y2L = $y1 + ($arm_size * sin(deg2rad($dir_angle + $arm_angle)));
			$x2R = $x1 + ($arm_size * cos(deg2rad($dir_angle - $arm_angle)));
			$y2R = $y1 + ($arm_size * sin(deg2rad($dir_angle - $arm_angle)));
			$mode = 'D';
			$style = array();

			switch ($head_style) {
			case 0:
				$mode = 'D';
				$style = array(1, 1, 0);
				break;

			case 1:
				$mode = 'D';
				break;

			case 2:
				$mode = 'DF';
				break;

			case 3:
				$mode = 'F';
				break;
			}

			$this->Polygon(array($x2L, $y2L, $x1, $y1, $x2R, $y2R), $mode, $style, array());
		}

		protected function utf8StrRev($str, $setbom = false, $forcertl = false)
		{
			return $this->arrUTF8ToUTF16BE($this->utf8Bidi($this->UTF8StringToArray($str), $str, $forcertl), $setbom);
		}

		protected function utf8Bidi($ta, $str = '', $forcertl = false)
		{
			global $unicode;
			global $unicode_mirror;
			global $unicode_arlet;
			global $laa_array;
			global $diacritics;
			$pel = 0;
			$maxlevel = 0;

			if ($this->empty_string($str)) {
				$str = $this->UTF8ArrSubString($ta);
			}

			if (preg_match(K_RE_PATTERN_ARABIC, $str)) {
				$arabic = true;
			}
			else {
				$arabic = false;
			}

			if (!($forcertl || $arabic || preg_match(K_RE_PATTERN_RTL, $str))) {
				return $ta;
			}

			$numchars = count($ta);

			if ($forcertl == 'R') {
				$pel = 1;
			}
			else if ($forcertl == 'L') {
				$pel = 0;
			}
			else {
				for ($i = 0; $i < $numchars; ++$i) {
					$type = $unicode[$ta[$i]];

					if ($type == 'L') {
						$pel = 0;
						break;
					}
					else {
						if (($type == 'AL') || ($type == 'R')) {
							$pel = 1;
							break;
						}
					}
				}
			}

			$cel = $pel;
			$dos = 'N';
			$remember = array();
			$sor = ($pel % 2 ? 'R' : 'L');
			$eor = $sor;
			$chardata = array();

			for ($i = 0; $i < $numchars; ++$i) {
				if ($ta[$i] == K_RLE) {
					$next_level = $cel + ($cel % 2) + 1;

					if ($next_level < 62) {
						$remember[] = array('num' => K_RLE, 'cel' => $cel, 'dos' => $dos);
						$cel = $next_level;
						$dos = 'N';
						$sor = $eor;
						$eor = ($cel % 2 ? 'R' : 'L');
					}
				}
				else if ($ta[$i] == K_LRE) {
					$next_level = ($cel + 2) - ($cel % 2);

					if ($next_level < 62) {
						$remember[] = array('num' => K_LRE, 'cel' => $cel, 'dos' => $dos);
						$cel = $next_level;
						$dos = 'N';
						$sor = $eor;
						$eor = ($cel % 2 ? 'R' : 'L');
					}
				}
				else if ($ta[$i] == K_RLO) {
					$next_level = $cel + ($cel % 2) + 1;

					if ($next_level < 62) {
						$remember[] = array('num' => K_RLO, 'cel' => $cel, 'dos' => $dos);
						$cel = $next_level;
						$dos = 'R';
						$sor = $eor;
						$eor = ($cel % 2 ? 'R' : 'L');
					}
				}
				else if ($ta[$i] == K_LRO) {
					$next_level = ($cel + 2) - ($cel % 2);

					if ($next_level < 62) {
						$remember[] = array('num' => K_LRO, 'cel' => $cel, 'dos' => $dos);
						$cel = $next_level;
						$dos = 'L';
						$sor = $eor;
						$eor = ($cel % 2 ? 'R' : 'L');
					}
				}
				else if ($ta[$i] == K_PDF) {
					if (count($remember)) {
						$last = count($remember) - 1;
						if (($remember[$last]['num'] == K_RLE) || ($remember[$last]['num'] == K_LRE) || ($remember[$last]['num'] == K_RLO) || ($remember[$last]['num'] == K_LRO)) {
							$match = array_pop($remember);
							$cel = $match['cel'];
							$dos = $match['dos'];
							$sor = $eor;
							$eor = (($match['cel'] < $cel ? $cel : $match['cel']) % 2 ? 'R' : 'L');
						}
					}
				}
				else {
					if (($ta[$i] != K_RLE) && ($ta[$i] != K_LRE) && ($ta[$i] != K_RLO) && ($ta[$i] != K_LRO) && ($ta[$i] != K_PDF)) {
						if ($dos != 'N') {
							$chardir = $dos;
						}
						else if (isset($unicode[$ta[$i]])) {
							$chardir = $unicode[$ta[$i]];
						}
						else {
							$chardir = 'L';
						}

						$chardata[] = array('char' => $ta[$i], 'level' => $cel, 'type' => $chardir, 'sor' => $sor, 'eor' => $eor);
					}
				}
			}

			$numchars = count($chardata);
			$prevlevel = -1;
			$levcount = 0;

			for ($i = 0; $i < $numchars; ++$i) {
				if ($chardata[$i]['type'] == 'NSM') {
					if ($levcount) {
						$chardata[$i]['type'] = $chardata[$i]['sor'];
					}
					else if (0 < $i) {
						$chardata[$i]['type'] = $chardata[$i - 1]['type'];
					}
				}

				if ($chardata[$i]['level'] != $prevlevel) {
					$levcount = 0;
				}
				else {
					++$levcount;
				}

				$prevlevel = $chardata[$i]['level'];
			}

			$prevlevel = -1;
			$levcount = 0;

			for ($i = 0; $i < $numchars; ++$i) {
				if ($chardata[$i]['char'] == 'EN') {
					for ($j = $levcount; 0 <= $j; $j--) {
						if ($chardata[$j]['type'] == 'AL') {
							$chardata[$i]['type'] = 'AN';
						}
						else {
							if (($chardata[$j]['type'] == 'L') || ($chardata[$j]['type'] == 'R')) {
								break;
							}
						}
					}
				}

				if ($chardata[$i]['level'] != $prevlevel) {
					$levcount = 0;
				}
				else {
					++$levcount;
				}

				$prevlevel = $chardata[$i]['level'];
			}

			for ($i = 0; $i < $numchars; ++$i) {
				if ($chardata[$i]['type'] == 'AL') {
					$chardata[$i]['type'] = 'R';
				}
			}

			$prevlevel = -1;
			$levcount = 0;

			for ($i = 0; $i < $numchars; ++$i) {
				if ((0 < $levcount) && (($i + 1) < $numchars) && ($chardata[$i + 1]['level'] == $prevlevel)) {
					if (($chardata[$i]['type'] == 'ES') && ($chardata[$i - 1]['type'] == 'EN') && ($chardata[$i + 1]['type'] == 'EN')) {
						$chardata[$i]['type'] = 'EN';
					}
					else {
						if (($chardata[$i]['type'] == 'CS') && ($chardata[$i - 1]['type'] == 'EN') && ($chardata[$i + 1]['type'] == 'EN')) {
							$chardata[$i]['type'] = 'EN';
						}
						else {
							if (($chardata[$i]['type'] == 'CS') && ($chardata[$i - 1]['type'] == 'AN') && ($chardata[$i + 1]['type'] == 'AN')) {
								$chardata[$i]['type'] = 'AN';
							}
						}
					}
				}

				if ($chardata[$i]['level'] != $prevlevel) {
					$levcount = 0;
				}
				else {
					++$levcount;
				}

				$prevlevel = $chardata[$i]['level'];
			}

			$prevlevel = -1;
			$levcount = 0;

			for ($i = 0; $i < $numchars; ++$i) {
				if ($chardata[$i]['type'] == 'ET') {
					if ((0 < $levcount) && ($chardata[$i - 1]['type'] == 'EN')) {
						$chardata[$i]['type'] = 'EN';
					}
					else {
						$j = $i + 1;

						while (($j < $numchars) && ($chardata[$j]['level'] == $prevlevel)) {
							if ($chardata[$j]['type'] == 'EN') {
								$chardata[$i]['type'] = 'EN';
								break;
							}
							else if ($chardata[$j]['type'] != 'ET') {
								break;
							}

							++$j;
						}
					}
				}

				if ($chardata[$i]['level'] != $prevlevel) {
					$levcount = 0;
				}
				else {
					++$levcount;
				}

				$prevlevel = $chardata[$i]['level'];
			}

			$prevlevel = -1;
			$levcount = 0;

			for ($i = 0; $i < $numchars; ++$i) {
				if (($chardata[$i]['type'] == 'ET') || ($chardata[$i]['type'] == 'ES') || ($chardata[$i]['type'] == 'CS')) {
					$chardata[$i]['type'] = 'ON';
				}

				if ($chardata[$i]['level'] != $prevlevel) {
					$levcount = 0;
				}
				else {
					++$levcount;
				}

				$prevlevel = $chardata[$i]['level'];
			}

			$prevlevel = -1;
			$levcount = 0;

			for ($i = 0; $i < $numchars; ++$i) {
				if ($chardata[$i]['char'] == 'EN') {
					for ($j = $levcount; 0 <= $j; $j--) {
						if ($chardata[$j]['type'] == 'L') {
							$chardata[$i]['type'] = 'L';
						}
						else if ($chardata[$j]['type'] == 'R') {
							break;
						}
					}
				}

				if ($chardata[$i]['level'] != $prevlevel) {
					$levcount = 0;
				}
				else {
					++$levcount;
				}

				$prevlevel = $chardata[$i]['level'];
			}

			$prevlevel = -1;
			$levcount = 0;

			for ($i = 0; $i < $numchars; ++$i) {
				if ((0 < $levcount) && (($i + 1) < $numchars) && ($chardata[$i + 1]['level'] == $prevlevel)) {
					if (($chardata[$i]['type'] == 'N') && ($chardata[$i - 1]['type'] == 'L') && ($chardata[$i + 1]['type'] == 'L')) {
						$chardata[$i]['type'] = 'L';
					}
					else {
						if (($chardata[$i]['type'] == 'N') && (($chardata[$i - 1]['type'] == 'R') || ($chardata[$i - 1]['type'] == 'EN') || ($chardata[$i - 1]['type'] == 'AN')) && (($chardata[$i + 1]['type'] == 'R') || ($chardata[$i + 1]['type'] == 'EN') || ($chardata[$i + 1]['type'] == 'AN'))) {
							$chardata[$i]['type'] = 'R';
						}
						else if ($chardata[$i]['type'] == 'N') {
							$chardata[$i]['type'] = $chardata[$i]['sor'];
						}
					}
				}
				else {
					if (($levcount == 0) && (($i + 1) < $numchars) && ($chardata[$i + 1]['level'] == $prevlevel)) {
						if (($chardata[$i]['type'] == 'N') && ($chardata[$i]['sor'] == 'L') && ($chardata[$i + 1]['type'] == 'L')) {
							$chardata[$i]['type'] = 'L';
						}
						else {
							if (($chardata[$i]['type'] == 'N') && (($chardata[$i]['sor'] == 'R') || ($chardata[$i]['sor'] == 'EN') || ($chardata[$i]['sor'] == 'AN')) && (($chardata[$i + 1]['type'] == 'R') || ($chardata[$i + 1]['type'] == 'EN') || ($chardata[$i + 1]['type'] == 'AN'))) {
								$chardata[$i]['type'] = 'R';
							}
							else if ($chardata[$i]['type'] == 'N') {
								$chardata[$i]['type'] = $chardata[$i]['sor'];
							}
						}
					}
					else {
						if ((0 < $levcount) && ((($i + 1) == $numchars) || ((($i + 1) < $numchars) && ($chardata[$i + 1]['level'] != $prevlevel)))) {
							if (($chardata[$i]['type'] == 'N') && ($chardata[$i - 1]['type'] == 'L') && ($chardata[$i]['eor'] == 'L')) {
								$chardata[$i]['type'] = 'L';
							}
							else {
								if (($chardata[$i]['type'] == 'N') && (($chardata[$i - 1]['type'] == 'R') || ($chardata[$i - 1]['type'] == 'EN') || ($chardata[$i - 1]['type'] == 'AN')) && (($chardata[$i]['eor'] == 'R') || ($chardata[$i]['eor'] == 'EN') || ($chardata[$i]['eor'] == 'AN'))) {
									$chardata[$i]['type'] = 'R';
								}
								else if ($chardata[$i]['type'] == 'N') {
									$chardata[$i]['type'] = $chardata[$i]['sor'];
								}
							}
						}
						else if ($chardata[$i]['type'] == 'N') {
							$chardata[$i]['type'] = $chardata[$i]['sor'];
						}
					}
				}

				if ($chardata[$i]['level'] != $prevlevel) {
					$levcount = 0;
				}
				else {
					++$levcount;
				}

				$prevlevel = $chardata[$i]['level'];
			}

			for ($i = 0; $i < $numchars; ++$i) {
				$odd = $chardata[$i]['level'] % 2;

				if ($odd) {
					if (($chardata[$i]['type'] == 'L') || ($chardata[$i]['type'] == 'AN') || ($chardata[$i]['type'] == 'EN')) {
						$chardata[$i]['level'] += 1;
					}
				}
				else if ($chardata[$i]['type'] == 'R') {
					$chardata[$i]['level'] += 1;
				}
				else {
					if (($chardata[$i]['type'] == 'AN') || ($chardata[$i]['type'] == 'EN')) {
						$chardata[$i]['level'] += 2;
					}
				}

				$maxlevel = max($chardata[$i]['level'], $maxlevel);
			}

			for ($i = 0; $i < $numchars; ++$i) {
				if (($chardata[$i]['type'] == 'B') || ($chardata[$i]['type'] == 'S')) {
					$chardata[$i]['level'] = $pel;
				}
				else if ($chardata[$i]['type'] == 'WS') {
					$j = $i + 1;

					while ($j < $numchars) {
						if (($chardata[$j]['type'] == 'B') || ($chardata[$j]['type'] == 'S') || (($j == $numchars - 1) && ($chardata[$j]['type'] == 'WS'))) {
							$chardata[$i]['level'] = $pel;
							break;
						}
						else if ($chardata[$j]['type'] != 'WS') {
							break;
						}

						++$j;
					}
				}
			}

			if ($arabic) {
				$endedletter = array(1569, 1570, 1571, 1572, 1573, 1575, 1577, 1583, 1584, 1585, 1586, 1608, 1688);
				$alfletter = array(1570, 1571, 1573, 1575);
				$chardata2 = $chardata;
				$laaletter = false;
				$charAL = array();
				$x = 0;

				for ($i = 0; $i < $numchars; ++$i) {
					if (($unicode[$chardata[$i]['char']] == 'AL') || ($chardata[$i]['char'] == 32) || ($chardata[$i]['char'] == 8204)) {
						$charAL[$x] = $chardata[$i];
						$charAL[$x]['i'] = $i;
						$chardata[$i]['x'] = $x;
						++$x;
					}
				}

				$numAL = $x;

				for ($i = 0; $i < $numchars; ++$i) {
					$thischar = $chardata[$i];

					if (0 < $i) {
						$prevchar = $chardata[$i - 1];
					}
					else {
						$prevchar = false;
					}

					if (($i + 1) < $numchars) {
						$nextchar = $chardata[$i + 1];
					}
					else {
						$nextchar = false;
					}

					if ($unicode[$thischar['char']] == 'AL') {
						$x = $thischar['x'];

						if (0 < $x) {
							$prevchar = $charAL[$x - 1];
						}
						else {
							$prevchar = false;
						}

						if (($x + 1) < $numAL) {
							$nextchar = $charAL[$x + 1];
						}
						else {
							$nextchar = false;
						}

						if (($prevchar !== false) && ($prevchar['char'] == 1604) && in_array($thischar['char'], $alfletter)) {
							$arabicarr = $laa_array;
							$laaletter = true;

							if (1 < $x) {
								$prevchar = $charAL[$x - 2];
							}
							else {
								$prevchar = false;
							}
						}
						else {
							$arabicarr = $unicode_arlet;
							$laaletter = false;
						}

						if (($prevchar !== false) && ($nextchar !== false) && (($unicode[$prevchar['char']] == 'AL') || ($unicode[$prevchar['char']] == 'NSM')) && (($unicode[$nextchar['char']] == 'AL') || ($unicode[$nextchar['char']] == 'NSM')) && ($prevchar['type'] == $thischar['type']) && ($nextchar['type'] == $thischar['type']) && ($nextchar['char'] != 1567)) {
							if (in_array($prevchar['char'], $endedletter)) {
								if (isset($arabicarr[$thischar['char']][2])) {
									$chardata2[$i]['char'] = $arabicarr[$thischar['char']][2];
								}
							}
							else if (isset($arabicarr[$thischar['char']][3])) {
								$chardata2[$i]['char'] = $arabicarr[$thischar['char']][3];
							}
						}
						else {
							if (($nextchar !== false) && (($unicode[$nextchar['char']] == 'AL') || ($unicode[$nextchar['char']] == 'NSM')) && ($nextchar['type'] == $thischar['type']) && ($nextchar['char'] != 1567)) {
								if (isset($arabicarr[$chardata[$i]['char']][2])) {
									$chardata2[$i]['char'] = $arabicarr[$thischar['char']][2];
								}
							}
							else {
								if ((($prevchar !== false) && (($unicode[$prevchar['char']] == 'AL') || ($unicode[$prevchar['char']] == 'NSM')) && ($prevchar['type'] == $thischar['type'])) || (($nextchar !== false) && ($nextchar['char'] == 1567))) {
									if ((1 < $i) && ($thischar['char'] == 1607) && ($chardata[$i - 1]['char'] == 1604) && ($chardata[$i - 2]['char'] == 1604)) {
										$chardata2[$i - 2]['char'] = false;
										$chardata2[$i - 1]['char'] = false;
										$chardata2[$i]['char'] = 65010;
									}
									else {
										if (($prevchar !== false) && in_array($prevchar['char'], $endedletter)) {
											if (isset($arabicarr[$thischar['char']][0])) {
												$chardata2[$i]['char'] = $arabicarr[$thischar['char']][0];
											}
										}
										else if (isset($arabicarr[$thischar['char']][1])) {
											$chardata2[$i]['char'] = $arabicarr[$thischar['char']][1];
										}
									}
								}
								else if (isset($arabicarr[$thischar['char']][0])) {
									$chardata2[$i]['char'] = $arabicarr[$thischar['char']][0];
								}
							}
						}

						if ($laaletter) {
							$chardata2[$charAL[$x - 1]['i']]['char'] = false;
						}
					}
				}

				$cw = &$this->CurrentFont['cw'];

				for ($i = 0; $i < ($numchars - 1); ++$i) {
					if (($chardata2[$i]['char'] == 1617) && isset($diacritics[$chardata2[$i + 1]['char']])) {
						if (isset($cw[$diacritics[$chardata2[$i + 1]['char']]])) {
							$chardata2[$i]['char'] = false;
							$chardata2[$i + 1]['char'] = $diacritics[$chardata2[$i + 1]['char']];
						}
					}
				}

				foreach ($chardata2 as $key => $value) {
					if ($value['char'] === false) {
						unset($chardata2[$key]);
					}
				}

				$chardata = array_values($chardata2);
				$numchars = count($chardata);
				unset($chardata2);
				unset($arabicarr);
				unset($laaletter);
				unset($charAL);
			}

			for ($j = $maxlevel; 0 < $j; $j--) {
				$ordarray = array();
				$revarr = array();
				$onlevel = false;

				for ($i = 0; $i < $numchars; ++$i) {
					if ($j <= $chardata[$i]['level']) {
						$onlevel = true;

						if (isset($unicode_mirror[$chardata[$i]['char']])) {
							$chardata[$i]['char'] = $unicode_mirror[$chardata[$i]['char']];
						}

						$revarr[] = $chardata[$i];
					}
					else {
						if ($onlevel) {
							$revarr = array_reverse($revarr);
							$ordarray = array_merge($ordarray, $revarr);
							$revarr = array();
							$onlevel = false;
						}

						$ordarray[] = $chardata[$i];
					}
				}

				if ($onlevel) {
					$revarr = array_reverse($revarr);
					$ordarray = array_merge($ordarray, $revarr);
				}

				$chardata = $ordarray;
			}

			$ordarray = array();

			for ($i = 0; $i < $numchars; ++$i) {
				$ordarray[] = $chardata[$i]['char'];
			}

			return $ordarray;
		}

		public function Bookmark($txt, $level = 0, $y = -1, $page = '')
		{
			if ($level < 0) {
				$level = 0;
			}

			if (isset($this->outlines[0])) {
				$lastoutline = end($this->outlines);
				$maxlevel = $lastoutline['l'] + 1;
			}
			else {
				$maxlevel = 0;
			}

			if ($maxlevel < $level) {
				$level = $maxlevel;
			}

			if ($y == -1) {
				$y = $this->GetY();
			}

			if (empty($page)) {
				$page = $this->PageNo();
			}

			$this->outlines[] = array('t' => $txt, 'l' => $level, 'y' => $y, 'p' => $page);
		}

		protected function _putbookmarks()
		{
			$nb = count($this->outlines);

			if ($nb == 0) {
				return NULL;
			}

			$lru = array();
			$level = 0;

			foreach ($this->outlines as $i => $o) {
				if (0 < $o['l']) {
					$parent = $lru[$o['l'] - 1];
					$this->outlines[$i]['parent'] = $parent;
					$this->outlines[$parent]['last'] = $i;

					if ($level < $o['l']) {
						$this->outlines[$parent]['first'] = $i;
					}
				}
				else {
					$this->outlines[$i]['parent'] = $nb;
				}

				if (($o['l'] <= $level) && (0 < $i)) {
					$prev = $lru[$o['l']];
					$this->outlines[$prev]['next'] = $i;
					$this->outlines[$i]['prev'] = $prev;
				}

				$lru[$o['l']] = $i;
				$level = $o['l'];
			}

			$n = $this->n + 1;

			foreach ($this->outlines as $i => $o) {
				$this->_newobj();
				$this->_out('<</Title ' . $this->_textstring($o['t']));
				$this->_out('/Parent ' . ($n + $o['parent']) . ' 0 R');

				if (isset($o['prev'])) {
					$this->_out('/Prev ' . ($n + $o['prev']) . ' 0 R');
				}

				if (isset($o['next'])) {
					$this->_out('/Next ' . ($n + $o['next']) . ' 0 R');
				}

				if (isset($o['first'])) {
					$this->_out('/First ' . ($n + $o['first']) . ' 0 R');
				}

				if (isset($o['last'])) {
					$this->_out('/Last ' . ($n + $o['last']) . ' 0 R');
				}

				$this->_out(sprintf('/Dest [%d 0 R /XYZ 0 %.2F null]', 1 + (2 * $o['p']), $this->pagedim[$o['p']]['h'] - ($o['y'] * $this->k)));
				$this->_out('/Count 0>>');
				$this->_out('endobj');
			}

			$this->_newobj();
			$this->OutlineRoot = $this->n;
			$this->_out('<</Type /Outlines /First ' . $n . ' 0 R');
			$this->_out('/Last ' . ($n + $lru[0]) . ' 0 R>>');
			$this->_out('endobj');
		}

		public function IncludeJS($script)
		{
			$this->javascript .= $script;
		}

		public function addJavascriptObject($script, $onload = false)
		{
			++$this->js_obj_id;
			$this->js_objects[$this->js_obj_id] = array('js' => $script, 'onload' => $onload);
			return $this->js_obj_id;
		}

		protected function _putjavascript()
		{
			if (empty($this->javascript) && empty($this->js_objects)) {
				return NULL;
			}

			if (0 < strpos($this->javascript, 'this.addField')) {
				if (!$this->ur) {
				}

				$jsa = sprintf('ftcpdfdocsaved=this.addField(\'%s\',\'%s\',%d,[%.2F,%.2F,%.2F,%.2F]);', 'tcpdfdocsaved', 'text', 0, 0, 1, 0, 1);
				$jsb = 'getField(\'tcpdfdocsaved\').value=\'saved\';';
				$this->javascript = $jsa . "\n" . $this->javascript . "\n" . $jsb;
			}

			$this->n_js = $this->_newobj();
			$this->_out('<<');
			$this->_out('/Names [');

			if (!empty($this->javascript)) {
				$this->_out('(EmbeddedJS) ' . ($this->n + 1) . ' 0 R');
			}

			if (!empty($this->js_objects)) {
				foreach ($this->js_objects as $key => $val) {
					if ($val['onload']) {
						$this->_out('(JS' . $key . ') ' . $key . ' 0 R');
					}
				}
			}

			$this->_out(']');
			$this->_out('>>');
			$this->_out('endobj');

			if (!empty($this->javascript)) {
				$this->_newobj();
				$this->_out('<<');
				$this->_out('/S /JavaScript');
				$this->_out('/JS ' . $this->_textstring($this->javascript));
				$this->_out('>>');
				$this->_out('endobj');
			}

			if (!empty($this->js_objects)) {
				foreach ($this->js_objects as $key => $val) {
					$this->offsets[$key] = $this->bufferlen;
					$this->_out($key . ' 0 obj');
					$this->_out('<<');
					$this->_out('/S /JavaScript');
					$this->_out('/JS ' . $this->_textstring($val['js']));
					$this->_out('>>');
					$this->_out('endobj');
				}
			}
		}

		protected function _JScolor($color)
		{
			static $aColors = array('transparent', 'black', 'white', 'red', 'green', 'blue', 'cyan', 'magenta', 'yellow', 'dkGray', 'gray', 'ltGray');

			if (substr($color, 0, 1) == '#') {
				return sprintf('[\'RGB\',%.3F,%.3F,%.3F]', hexdec(substr($color, 1, 2)) / 255, hexdec(substr($color, 3, 2)) / 255, hexdec(substr($color, 5, 2)) / 255);
			}

			if (!in_array($color, $aColors)) {
				$this->Error('Invalid color: ' . $color);
			}

			return 'color.' . $color;
		}

		protected function _addfield($type, $name, $x, $y, $w, $h, $prop)
		{
			if ($this->rtl) {
				$x = $x - $w;
			}

			$this->javascript .= 'if(getField(\'tcpdfdocsaved\').value != \'saved\') {';
			$k = $this->k;
			$this->javascript .= sprintf('f' . $name . '=this.addField(\'%s\',\'%s\',%d,[%.2F,%.2F,%.2F,%.2F]);', $name, $type, $this->PageNo() - 1, $x * $k, (($this->h - $y) * $k) + 1, ($x + $w) * $k, (($this->h - $y - $h) * $k) + 1) . "\n";
			$this->javascript .= 'f' . $name . '.textSize=' . $this->FontSizePt . ';' . "\n" . '';

			while (list($key, $val) = each($prop)) {
				if (strcmp(substr($key, -5), 'Color') == 0) {
					$val = $this->_JScolor($val);
				}
				else {
					$val = '\'' . $val . '\'';
				}

				$this->javascript .= 'f' . $name . '.' . $key . '=' . $val . ';' . "\n" . '';
			}

			if ($this->rtl) {
				$this->x -= $w;
			}
			else {
				$this->x += $w;
			}

			$this->javascript .= '}';
		}

		protected function getAnnotOptFromJSProp($prop)
		{
			if (isset($prop['aopt']) && is_array($prop['aopt'])) {
				return $prop['aopt'];
			}

			$opt = array();

			if (isset($prop['alignment'])) {
				switch ($prop['alignment']) {
				case 'left':
					$opt['q'] = 0;
					break;

				case 'center':
					$opt['q'] = 1;
					break;

				case 'right':
					$opt['q'] = 2;
					break;

				default:
					$opt['q'] = $this->rtl ? 2 : 0;
					break;
				}
			}

			if (isset($prop['lineWidth'])) {
				$linewidth = intval($prop['lineWidth']);
			}
			else {
				$linewidth = 1;
			}

			if (isset($prop['borderStyle'])) {
				switch ($prop['borderStyle']) {
				case 'border.d':
				case 'dashed':
					$opt['border'] = array(
	0,
	0,
	$linewidth,
	array(3, 2)
	);
					$opt['bs'] = array(
	'w' => $linewidth,
	's' => 'D',
	'd' => array(3, 2)
	);
					break;

				case 'border.b':
				case 'beveled':
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w' => $linewidth, 's' => 'B');
					break;

				case 'border.i':
				case 'inset':
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w' => $linewidth, 's' => 'I');
					break;

				case 'border.u':
				case 'underline':
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w' => $linewidth, 's' => 'U');
					break;

				default:
				case 'border.s':
				case 'solid':
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w' => $linewidth, 's' => 'S');
					break;
				}
			}

			if (isset($prop['border']) && is_array($prop['border'])) {
				$opt['border'] = $prop['border'];
			}

			if (!isset($opt['mk'])) {
				$opt['mk'] = array();
			}

			if (!isset($opt['mk']['if'])) {
				$opt['mk']['if'] = array();
			}

			$opt['mk']['if']['a'] = array(0.5, 0.5);

			if (isset($prop['buttonAlignX'])) {
				$opt['mk']['if']['a'][0] = $prop['buttonAlignX'];
			}

			if (isset($prop['buttonAlignY'])) {
				$opt['mk']['if']['a'][1] = $prop['buttonAlignY'];
			}

			if (isset($prop['buttonFitBounds']) && ($prop['buttonFitBounds'] == 'true')) {
				$opt['mk']['if']['fb'] = true;
			}

			if (isset($prop['buttonScaleHow'])) {
				switch ($prop['buttonScaleHow']) {
				case 'scaleHow.proportional':
					$opt['mk']['if']['s'] = 'P';
					break;

				case 'scaleHow.anamorphic':
					$opt['mk']['if']['s'] = 'A';
					break;
				}
			}

			if (isset($prop['buttonScaleWhen'])) {
				switch ($prop['buttonScaleWhen']) {
				case 'scaleWhen.always':
					$opt['mk']['if']['sw'] = 'A';
					break;

				case 'scaleWhen.never':
					$opt['mk']['if']['sw'] = 'N';
					break;

				case 'scaleWhen.tooBig':
					$opt['mk']['if']['sw'] = 'B';
					break;

				case 'scaleWhen.tooSmall':
					$opt['mk']['if']['sw'] = 'S';
					break;
				}
			}

			if (isset($prop['buttonPosition'])) {
				switch ($prop['buttonPosition']) {
				case 0:
				case 'position.textOnly':
					$opt['mk']['tp'] = 0;
					break;

				case 1:
				case 'position.iconOnly':
					$opt['mk']['tp'] = 1;
					break;

				case 2:
				case 'position.iconTextV':
					$opt['mk']['tp'] = 2;
					break;

				case 3:
				case 'position.textIconV':
					$opt['mk']['tp'] = 3;
					break;

				case 4:
				case 'position.iconTextH':
					$opt['mk']['tp'] = 4;
					break;

				case 5:
				case 'position.textIconH':
					$opt['mk']['tp'] = 5;
					break;

				case 6:
				case 'position.overlay':
					$opt['mk']['tp'] = 6;
					break;
				}
			}

			if (isset($prop['fillColor'])) {
				if (is_array($prop['fillColor'])) {
					$opt['mk']['bg'] = $prop['fillColor'];
				}
				else {
					$opt['mk']['bg'] = $this->convertHTMLColorToDec($prop['fillColor']);
				}
			}

			if (isset($prop['strokeColor'])) {
				if (is_array($prop['strokeColor'])) {
					$opt['mk']['bc'] = $prop['strokeColor'];
				}
				else {
					$opt['mk']['bc'] = $this->convertHTMLColorToDec($prop['strokeColor']);
				}
			}

			if (isset($prop['rotation'])) {
				$opt['mk']['r'] = $prop['rotation'];
			}

			if (isset($prop['charLimit'])) {
				$opt['maxlen'] = intval($prop['charLimit']);
			}

			if (!isset($ff)) {
				$ff = 0;
			}

			if (isset($prop['readonly']) && ($prop['readonly'] == 'true')) {
				$ff += 1 << 0;
			}

			if (isset($prop['required']) && ($prop['required'] == 'true')) {
				$ff += 1 << 1;
			}

			if (isset($prop['multiline']) && ($prop['multiline'] == 'true')) {
				$ff += 1 << 12;
			}

			if (isset($prop['password']) && ($prop['password'] == 'true')) {
				$ff += 1 << 13;
			}

			if (isset($prop['NoToggleToOff']) && ($prop['NoToggleToOff'] == 'true')) {
				$ff += 1 << 14;
			}

			if (isset($prop['Radio']) && ($prop['Radio'] == 'true')) {
				$ff += 1 << 15;
			}

			if (isset($prop['Pushbutton']) && ($prop['Pushbutton'] == 'true')) {
				$ff += 1 << 16;
			}

			if (isset($prop['Combo']) && ($prop['Combo'] == 'true')) {
				$ff += 1 << 17;
			}

			if (isset($prop['editable']) && ($prop['editable'] == 'true')) {
				$ff += 1 << 18;
			}

			if (isset($prop['Sort']) && ($prop['Sort'] == 'true')) {
				$ff += 1 << 19;
			}

			if (isset($prop['fileSelect']) && ($prop['fileSelect'] == 'true')) {
				$ff += 1 << 20;
			}

			if (isset($prop['multipleSelection']) && ($prop['multipleSelection'] == 'true')) {
				$ff += 1 << 21;
			}

			if (isset($prop['doNotSpellCheck']) && ($prop['doNotSpellCheck'] == 'true')) {
				$ff += 1 << 22;
			}

			if (isset($prop['doNotScroll']) && ($prop['doNotScroll'] == 'true')) {
				$ff += 1 << 23;
			}

			if (isset($prop['comb']) && ($prop['comb'] == 'true')) {
				$ff += 1 << 24;
			}

			if (isset($prop['radiosInUnison']) && ($prop['radiosInUnison'] == 'true')) {
				$ff += 1 << 25;
			}

			if (isset($prop['richText']) && ($prop['richText'] == 'true')) {
				$ff += 1 << 25;
			}

			if (isset($prop['commitOnSelChange']) && ($prop['commitOnSelChange'] == 'true')) {
				$ff += 1 << 26;
			}

			$opt['ff'] = $ff;

			if (isset($prop['defaultValue'])) {
				$opt['dv'] = $prop['defaultValue'];
			}

			$f = 4;
			if (isset($prop['readonly']) && ($prop['readonly'] == 'true')) {
				$f += 1 << 6;
			}

			if (isset($prop['display'])) {
				if ($prop['display'] == 'display.visible') {
				}
				else if ($prop['display'] == 'display.hidden') {
					$f += 1 << 1;
				}
				else if ($prop['display'] == 'display.noPrint') {
					$f -= 1 << 2;
				}
				else if ($prop['display'] == 'display.noView') {
					$f += 1 << 5;
				}
			}

			$opt['f'] = $f;
			if (isset($prop['currentValueIndices']) && is_array($prop['currentValueIndices'])) {
				$opt['i'] = $prop['currentValueIndices'];
			}

			if (isset($prop['value'])) {
				if (is_array($prop['value'])) {
					$opt['opt'] = array();

					foreach ($prop['value'] as $key => $optval) {
						if (isset($prop['exportValues'][$key])) {
							$opt['opt'][$key] = array($prop['exportValues'][$key], $prop['value'][$key]);
						}
						else {
							$opt['opt'][$key] = $prop['value'][$key];
						}
					}
				}
				else {
					$opt['v'] = $prop['value'];
				}
			}

			if (isset($prop['richValue'])) {
				$opt['rv'] = $prop['richValue'];
			}

			if (isset($prop['submitName'])) {
				$opt['tm'] = $prop['submitName'];
			}

			if (isset($prop['name'])) {
				$opt['t'] = $prop['name'];
			}

			if (isset($prop['userName'])) {
				$opt['tu'] = $prop['userName'];
			}

			if (isset($prop['highlight'])) {
				switch ($prop['highlight']) {
				case 'none':
				case 'highlight.n':
					$opt['h'] = 'N';
					break;

				case 'invert':
				case 'highlight.i':
					$opt['h'] = 'i';
					break;

				case 'push':
				case 'highlight.p':
					$opt['h'] = 'P';
					break;

				case 'outline':
				case 'highlight.o':
					$opt['h'] = 'O';
					break;
				}
			}

			return $opt;
		}

		public function setFormDefaultProp($prop = array())
		{
			$this->default_form_prop = $prop;
		}

		public function getFormDefaultProp()
		{
			return $this->default_form_prop;
		}

		public function TextField($name, $w, $h, $prop = array(), $opt = array(), $x = '', $y = '', $js = false)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($js) {
				$this->_addfield('text', $name, $x, $y, $w, $h, $prop);
				return NULL;
			}

			$prop = array_merge($this->getFormDefaultProp(), $prop);
			$popt = $this->getAnnotOptFromJSProp($prop);
			$font = $this->FontFamily;
			$fontkey = array_search($font, $this->fontkeys);

			if (!in_array($fontkey, $this->annotation_fonts)) {
				$this->annotation_fonts[$font] = $fontkey;
			}

			$fontstyle = sprintf('/F%d %.2F Tf %s', $fontkey + 1, $this->FontSizePt, $this->TextColor);
			$popt['da'] = $fontstyle;
			$popt['ap'] = array();
			$popt['ap']['n'] = 'q BT ' . $fontstyle . ' ET Q';
			$opt = array_merge($popt, $opt);
			unset($opt['bs']);
			$opt['Subtype'] = 'Widget';
			$opt['ft'] = 'Tx';
			$opt['t'] = $name;
			$this->Annotation($x, $y, $w, $h, $name, $opt, 0);

			if ($this->rtl) {
				$this->x -= $w;
			}
			else {
				$this->x += $w;
			}
		}

		public function RadioButton($name, $w, $prop = array(), $opt = array(), $onvalue = 'On', $checked = false, $x = '', $y = '', $js = false)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($js) {
				$this->_addfield('radiobutton', $name, $x, $y, $w, $w, $prop);
				return NULL;
			}

			if ($this->empty_string($onvalue)) {
				$onvalue = 'On';
			}

			if ($checked) {
				$defval = $onvalue;
			}
			else {
				$defval = 'Off';
			}

			if (!isset($this->radiobutton_groups[$this->page])) {
				$this->radiobutton_groups[$this->page] = array();
			}

			if (!isset($this->radiobutton_groups[$this->page][$name])) {
				$this->radiobutton_groups[$this->page][$name] = array();
				++$this->annot_obj_id;
				$this->radio_groups[] = $this->annot_obj_id;
			}

			$this->radiobutton_groups[$this->page][$name][] = array('kid' => $this->annot_obj_id + 1, 'def' => $defval);
			$prop = array_merge($this->getFormDefaultProp(), $prop);
			$prop['NoToggleToOff'] = 'true';
			$prop['Radio'] = 'true';
			$prop['borderStyle'] = 'inset';
			$popt = $this->getAnnotOptFromJSProp($prop);
			$font = 'zapfdingbats';
			$this->AddFont($font);
			$fontkey = array_search($font, $this->fontkeys);

			if (!in_array($fontkey, $this->annotation_fonts)) {
				$this->annotation_fonts[$font] = $fontkey;
			}

			$fontstyle = sprintf('/F%d %.2F Tf %s', $fontkey + 1, $this->FontSizePt, $this->TextColor);
			$popt['da'] = $fontstyle;
			$popt['ap'] = array();
			$popt['ap']['n'] = array();
			$popt['ap']['n'][$onvalue] = 'q BT ' . $fontstyle . ' 0 0 Td (8) Tj ET Q';
			$popt['ap']['n']['Off'] = 'q BT ' . $fontstyle . ' 0 0 Td (8) Tj ET Q';

			if (!isset($popt['mk'])) {
				$popt['mk'] = array();
			}

			$popt['mk']['ca'] = '(l)';
			$opt = array_merge($popt, $opt);
			$opt['Subtype'] = 'Widget';
			$opt['ft'] = 'Btn';

			if ($checked) {
				$opt['v'] = array('/' . $onvalue);
				$opt['as'] = $onvalue;
			}
			else {
				$opt['as'] = 'Off';
			}

			$this->Annotation($x, $y, $w, $w, $name, $opt, 0);

			if ($this->rtl) {
				$this->x -= $w;
			}
			else {
				$this->x += $w;
			}
		}

		public function ListBox($name, $w, $h, $values, $prop = array(), $opt = array(), $x = '', $y = '', $js = false)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($js) {
				$this->_addfield('listbox', $name, $x, $y, $w, $h, $prop);
				$s = '';

				foreach ($values as $value) {
					$s .= '\'' . addslashes($value) . '\',';
				}

				$this->javascript .= 'f' . $name . '.setItems([' . substr($s, 0, -1) . ']);' . "\n" . '';
				return NULL;
			}

			$prop = array_merge($this->getFormDefaultProp(), $prop);
			$popt = $this->getAnnotOptFromJSProp($prop);
			$font = $this->FontFamily;
			$fontkey = array_search($font, $this->fontkeys);

			if (!in_array($fontkey, $this->annotation_fonts)) {
				$this->annotation_fonts[$font] = $fontkey;
			}

			$fontstyle = sprintf('/F%d %.2F Tf %s', $fontkey + 1, $this->FontSizePt, $this->TextColor);
			$popt['da'] = $fontstyle;
			$popt['ap'] = array();
			$popt['ap']['n'] = 'q BT ' . $fontstyle . ' ET Q';
			$opt = array_merge($popt, $opt);
			$opt['Subtype'] = 'Widget';
			$opt['ft'] = 'Ch';
			$opt['t'] = $name;
			$opt['opt'] = $values;
			$this->Annotation($x, $y, $w, $h, $name, $opt, 0);

			if ($this->rtl) {
				$this->x -= $w;
			}
			else {
				$this->x += $w;
			}
		}

		public function ComboBox($name, $w, $h, $values, $prop = array(), $opt = array(), $x = '', $y = '', $js = false)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($js) {
				$this->_addfield('combobox', $name, $x, $y, $w, $h, $prop);
				$s = '';

				foreach ($values as $value) {
					$s .= '\'' . addslashes($value) . '\',';
				}

				$this->javascript .= 'f' . $name . '.setItems([' . substr($s, 0, -1) . ']);' . "\n" . '';
				return NULL;
			}

			$prop = array_merge($this->getFormDefaultProp(), $prop);
			$prop['Combo'] = true;
			$popt = $this->getAnnotOptFromJSProp($prop);
			$font = $this->FontFamily;
			$fontkey = array_search($font, $this->fontkeys);

			if (!in_array($fontkey, $this->annotation_fonts)) {
				$this->annotation_fonts[$font] = $fontkey;
			}

			$fontstyle = sprintf('/F%d %.2F Tf %s', $fontkey + 1, $this->FontSizePt, $this->TextColor);
			$popt['da'] = $fontstyle;
			$popt['ap'] = array();
			$popt['ap']['n'] = 'q BT ' . $fontstyle . ' ET Q';
			$opt = array_merge($popt, $opt);
			$opt['Subtype'] = 'Widget';
			$opt['ft'] = 'Ch';
			$opt['t'] = $name;
			$opt['opt'] = $values;
			$this->Annotation($x, $y, $w, $h, $name, $opt, 0);

			if ($this->rtl) {
				$this->x -= $w;
			}
			else {
				$this->x += $w;
			}
		}

		public function CheckBox($name, $w, $checked = false, $prop = array(), $opt = array(), $onvalue = 'Yes', $x = '', $y = '', $js = false)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($js) {
				$this->_addfield('checkbox', $name, $x, $y, $w, $w, $prop);
				return NULL;
			}

			if (!isset($prop['value'])) {
				$prop['value'] = array('Yes');
			}

			$prop = array_merge($this->getFormDefaultProp(), $prop);
			$prop['borderStyle'] = 'inset';
			$popt = $this->getAnnotOptFromJSProp($prop);
			$font = 'zapfdingbats';
			$this->AddFont($font);
			$fontkey = array_search($font, $this->fontkeys);

			if (!in_array($fontkey, $this->annotation_fonts)) {
				$this->annotation_fonts[$font] = $fontkey;
			}

			$fontstyle = sprintf('/F%d %.2F Tf %s', $fontkey + 1, $this->FontSizePt, $this->TextColor);
			$popt['da'] = $fontstyle;
			$popt['ap'] = array();
			$popt['ap']['n'] = array();
			$popt['ap']['n']['Yes'] = 'q BT ' . $fontstyle . ' 0 0 Td (8) Tj ET Q';
			$popt['ap']['n']['Off'] = 'q BT ' . $fontstyle . ' 0 0 Td (8) Tj ET Q';
			$opt = array_merge($popt, $opt);
			$opt['Subtype'] = 'Widget';
			$opt['ft'] = 'Btn';
			$opt['t'] = $name;
			$opt['opt'] = array($onvalue);

			if ($checked) {
				$opt['v'] = array('/0');
				$opt['as'] = 'Yes';
			}
			else {
				$opt['v'] = array('/Off');
				$opt['as'] = 'Off';
			}

			$this->Annotation($x, $y, $w, $w, $name, $opt, 0);

			if ($this->rtl) {
				$this->x -= $w;
			}
			else {
				$this->x += $w;
			}
		}

		public function Button($name, $w, $h, $caption, $action, $prop = array(), $opt = array(), $x = '', $y = '', $js = false)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			if ($js) {
				$this->_addfield('button', $name, $this->x, $this->y, $w, $h, $prop);
				$this->javascript .= 'f' . $name . '.buttonSetCaption(\'' . addslashes($caption) . '\');' . "\n" . '';
				$this->javascript .= 'f' . $name . '.setAction(\'MouseUp\',\'' . addslashes($action) . '\');' . "\n" . '';
				$this->javascript .= 'f' . $name . '.highlight=\'push\';' . "\n" . '';
				$this->javascript .= 'f' . $name . '.print=false;' . "\n" . '';
				return NULL;
			}

			$prop = array_merge($this->getFormDefaultProp(), $prop);
			$prop['Pushbutton'] = 'true';
			$prop['highlight'] = 'push';
			$prop['display'] = 'display.noPrint';
			$popt = $this->getAnnotOptFromJSProp($prop);

			if (!isset($popt['mk'])) {
				$popt['mk'] = array();
			}

			$popt['mk']['ca'] = $this->_textstring($caption);
			$popt['mk']['rc'] = $this->_textstring($caption);
			$popt['mk']['ac'] = $this->_textstring($caption);
			$font = $this->FontFamily;
			$fontkey = array_search($font, $this->fontkeys);

			if (!in_array($fontkey, $this->annotation_fonts)) {
				$this->annotation_fonts[$font] = $fontkey;
			}

			$fontstyle = sprintf('/F%d %.2F Tf %s', $fontkey + 1, $this->FontSizePt, $this->TextColor);
			$popt['da'] = $fontstyle;
			$popt['ap'] = array();
			$popt['ap']['n'] = 'q BT ' . $fontstyle . ' ET Q';
			$opt = array_merge($popt, $opt);
			$opt['Subtype'] = 'Widget';
			$opt['ft'] = 'Btn';
			$opt['t'] = $caption;
			$opt['v'] = $name;

			if (!empty($action)) {
				if (is_array($action)) {
					$opt['aa'] = '/D <<';
					$bmode = array('SubmitForm', 'ResetForm', 'ImportData');

					foreach ($action as $key => $val) {
						if (($key == 'S') && in_array($val, $bmode)) {
							$opt['aa'] .= ' /S /' . $val;
						}
						else {
							if (($key == 'F') && !empty($val)) {
								$opt['aa'] .= ' /F ' . $this->_datastring($val);
							}
							else {
								if (($key == 'Fields') && is_array($val) && !empty($val)) {
									$opt['aa'] .= ' /Fields [';

									foreach ($val as $field) {
										$opt['aa'] .= ' ' . $this->_textstring($field);
									}

									$opt['aa'] .= ']';
								}
								else if ($key == 'Flags') {
									$ff = 0;

									if (is_array($val)) {
										foreach ($val as $flag) {
											switch ($flag) {
											case 'Include/Exclude':
												$ff += 1 << 0;
												break;

											case 'IncludeNoValueFields':
												$ff += 1 << 1;
												break;

											case 'ExportFormat':
												$ff += 1 << 2;
												break;

											case 'GetMethod':
												$ff += 1 << 3;
												break;

											case 'SubmitCoordinates':
												$ff += 1 << 4;
												break;

											case 'XFDF':
												$ff += 1 << 5;
												break;

											case 'IncludeAppendSaves':
												$ff += 1 << 6;
												break;

											case 'IncludeAnnotations':
												$ff += 1 << 7;
												break;

											case 'SubmitPDF':
												$ff += 1 << 8;
												break;

											case 'CanonicalFormat':
												$ff += 1 << 9;
												break;

											case 'ExclNonUserAnnots':
												$ff += 1 << 10;
												break;

											case 'ExclFKey':
												$ff += 1 << 11;
												break;

											case 'EmbedForm':
												$ff += 1 << 13;
												break;
											}
										}
									}
									else {
										$ff = intval($val);
									}

									$opt['aa'] .= ' /Flags ' . $ff;
								}
							}
						}
					}

					$opt['aa'] .= ' >>';
				}
				else {
					$js_obj_id = $this->addJavascriptObject($action);
					$opt['aa'] = '/D ' . $js_obj_id . ' 0 R';
				}
			}

			$this->Annotation($x, $y, $w, $h, $name, $opt, 0);

			if ($this->rtl) {
				$this->x -= $w;
			}
			else {
				$this->x += $w;
			}
		}

		protected function _putsignature()
		{
			if (!$this->sign || !isset($this->signature_data['cert_type'])) {
				return NULL;
			}

			$this->_out('/Type /Sig');
			$this->_out('/Filter /Adobe.PPKLite');
			$this->_out('/SubFilter /adbe.pkcs7.detached');
			$this->_out($this->byterange_string);
			$this->_out('/Contents<>' . str_repeat(' ', $this->signature_max_lenght));
			$this->_out('/Reference');
			$this->_out('[');
			$this->_out('<<');
			$this->_out('/Type /SigRef');

			if (0 < $this->signature_data['cert_type']) {
				$this->_out('/TransformMethod /DocMDP');
				$this->_out('/TransformParams');
				$this->_out('<<');
				$this->_out('/Type /TransformParams');
				$this->_out('/V /1.2');
				$this->_out('/P ' . $this->signature_data['cert_type'] . '');
			}
			else {
				$this->_out('/TransformMethod /UR3');
				$this->_out('/TransformParams');
				$this->_out('<<');
				$this->_out('/Type /TransformParams');
				$this->_out('/V /2.2');

				if (!$this->empty_string($this->ur_document)) {
					$this->_out('/Document[' . $this->ur_document . ']');
				}

				if (!$this->empty_string($this->ur_annots)) {
					$this->_out('/Annots[' . $this->ur_annots . ']');
				}

				if (!$this->empty_string($this->ur_form)) {
					$this->_out('/Form[' . $this->ur_form . ']');
				}

				if (!$this->empty_string($this->ur_signature)) {
					$this->_out('/Signature[' . $this->ur_signature . ']');
				}
			}

			$this->_out('>>');
			$this->_out('>>');
			$this->_out(']');
			if (isset($this->signature_data['info']['Name']) && !$this->empty_string($this->signature_data['info']['Name'])) {
				$this->_out('/Name ' . $this->_textstring($this->signature_data['info']['Name']) . '');
			}

			if (isset($this->signature_data['info']['Location']) && !$this->empty_string($this->signature_data['info']['Location'])) {
				$this->_out('/Location ' . $this->_textstring($this->signature_data['info']['Location']) . '');
			}

			if (isset($this->signature_data['info']['Reason']) && !$this->empty_string($this->signature_data['info']['Reason'])) {
				$this->_out('/Reason ' . $this->_textstring($this->signature_data['info']['Reason']) . '');
			}

			if (isset($this->signature_data['info']['ContactInfo']) && !$this->empty_string($this->signature_data['info']['ContactInfo'])) {
				$this->_out('/ContactInfo ' . $this->_textstring($this->signature_data['info']['ContactInfo']) . '');
			}

			$this->_out('/M ' . $this->_datestring());
		}

		public function setUserRights($enable = true, $document = '/FullSave', $annots = '/Create/Delete/Modify/Copy/Import/Export', $form = '/Add/Delete/FillIn/Import/Export/SubmitStandalone/SpawnTemplate', $signature = '/Modify')
		{
			$this->ur = $enable;
			$this->ur_document = $document;
			$this->ur_annots = $annots;
			$this->ur_form = $form;
			$this->ur_signature = $signature;

			if (!$this->sign) {
				$this->setSignature('', '', '', '', 0, array());
			}
		}

		public function setSignature($signing_cert = '', $private_key = '', $private_key_password = '', $extracerts = '', $cert_type = 2, $info = array())
		{
			$this->sign = true;
			$this->signature_data = array();

			if (strlen($signing_cert) == 0) {
				$signing_cert = 'file://' . dirname(__FILE__) . '/tcpdf.crt';
				$private_key_password = 'tcpdfdemo';
			}

			if (strlen($private_key) == 0) {
				$private_key = $signing_cert;
			}

			$this->signature_data['signcert'] = $signing_cert;
			$this->signature_data['privkey'] = $private_key;
			$this->signature_data['password'] = $private_key_password;
			$this->signature_data['extracerts'] = $extracerts;
			$this->signature_data['cert_type'] = $cert_type;
			$this->signature_data['info'] = $info;
		}

		public function startPageGroup($page = '')
		{
			if (empty($page)) {
				$page = $this->page + 1;
			}

			$this->newpagegroup[$page] = true;
		}

		public function AliasNbPages($alias = '{nb}')
		{
			$this->AliasNbPages = $alias;
		}

		public function getAliasNbPages()
		{
			if (($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) {
				return '{' . $this->AliasNbPages . '}';
			}

			return $this->AliasNbPages;
		}

		public function AliasNumPage($alias = '{pnb}')
		{
			$this->AliasNumPage = $alias;
		}

		public function getAliasNumPage()
		{
			if (($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) {
				return '{' . $this->AliasNumPage . '}';
			}

			return $this->AliasNumPage;
		}

		public function getGroupPageNo()
		{
			return $this->pagegroups[$this->currpagegroup];
		}

		public function getGroupPageNoFormatted()
		{
			return $this->formatPageNumber($this->getGroupPageNo());
		}

		public function getPageGroupAlias()
		{
			if (($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) {
				return '{' . $this->currpagegroup . '}';
			}

			return $this->currpagegroup;
		}

		public function getPageNumGroupAlias()
		{
			if (($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) {
				return '{' . str_replace('{nb', '{pnb', $this->currpagegroup) . '}';
			}

			return str_replace('{nb', '{pnb', $this->currpagegroup);
		}

		protected function formatPageNumber($num)
		{
			return number_format((double) $num, 0, '', '.');
		}

		protected function formatTOCPageNumber($num)
		{
			return number_format((double) $num, 0, '', '.');
		}

		public function PageNoFormatted()
		{
			return $this->formatPageNumber($this->PageNo());
		}

		protected function _putocg()
		{
			$this->_newobj();
			$this->n_ocg_print = $this->n;
			$this->_out('<</Type /OCG /Name ' . $this->_textstring('print'));
			$this->_out('/Usage <</Print <</PrintState /ON>> /View <</ViewState /OFF>>>>>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->n_ocg_view = $this->n;
			$this->_out('<</Type /OCG /Name ' . $this->_textstring('view'));
			$this->_out('/Usage <</Print <</PrintState /OFF>> /View <</ViewState /ON>>>>>>');
			$this->_out('endobj');
		}

		public function setVisibility($v)
		{
			if ($this->openMarkedContent) {
				$this->_out('EMC');
				$this->openMarkedContent = false;
			}

			switch ($v) {
			case 'print':
				$this->_out('/OC /OC1 BDC');
				$this->openMarkedContent = true;
				break;

			case 'screen':
				$this->_out('/OC /OC2 BDC');
				$this->openMarkedContent = true;
				break;

			case 'all':
				$this->_out('');
				break;

			default:
				$this->Error('Incorrect visibility: ' . $v);
				break;
			}

			$this->visibility = $v;
		}

		protected function addExtGState($parms)
		{
			$n = count($this->extgstates) + 1;
			$this->extgstates[$n]['parms'] = $parms;
			return $n;
		}

		protected function setExtGState($gs)
		{
			$this->_out(sprintf('/GS%d gs', $gs));
		}

		protected function _putextgstates()
		{
			$ne = count($this->extgstates);

			for ($i = 1; $i <= $ne; ++$i) {
				$this->_newobj();
				$this->extgstates[$i]['n'] = $this->n;
				$this->_out('<</Type /ExtGState');

				foreach ($this->extgstates[$i]['parms'] as $k => $v) {
					$this->_out('/' . $k . ' ' . $v);
				}

				$this->_out('>>');
				$this->_out('endobj');
			}
		}

		public function setAlpha($alpha, $bm = 'Normal')
		{
			$gs = $this->addExtGState(array('ca' => $alpha, 'CA' => $alpha, 'BM' => '/' . $bm));
			$this->setExtGState($gs);
		}

		public function setJPEGQuality($quality)
		{
			if (($quality < 1) || (100 < $quality)) {
				$quality = 75;
			}

			$this->jpeg_quality = intval($quality);
		}

		public function setDefaultTableColumns($cols = 4)
		{
			$this->default_table_columns = intval($cols);
		}

		public function setCellHeightRatio($h)
		{
			$this->cell_height_ratio = $h;
		}

		public function getCellHeightRatio()
		{
			return $this->cell_height_ratio;
		}

		public function setPDFVersion($version = '1.7')
		{
			$this->PDFVersion = $version;
		}

		public function setViewerPreferences($preferences)
		{
			$this->viewer_preferences = $preferences;
		}

		public function LinearGradient($x, $y, $w, $h, $col1 = array(), $col2 = array(), $coords = array(0, 0, 1, 0))
		{
			$this->Clip($x, $y, $w, $h);
			$this->Gradient(2, $col1, $col2, $coords);
		}

		public function RadialGradient($x, $y, $w, $h, $col1 = array(), $col2 = array(), $coords = array(0.5, 0.5, 0.5, 0.5, 1))
		{
			$this->Clip($x, $y, $w, $h);
			$this->Gradient(3, $col1, $col2, $coords);
		}

		public function CoonsPatchMesh($x, $y, $w, $h, $col1 = array(), $col2 = array(), $col3 = array(), $col4 = array(), $coords = array(0, 0, 0.33, 0, 0.67, 0, 1, 0, 1, 0.33, 1, 0.67, 1, 1, 0.67, 1, 0.33, 1, 0, 1, 0, 0.67, 0, 0.33), $coords_min = 0, $coords_max = 1)
		{
			$this->Clip($x, $y, $w, $h);
			$n = count($this->gradients) + 1;
			$this->gradients[$n]['type'] = 6;

			if (!isset($coords[0]['f'])) {
				if (!isset($col1[1])) {
					$col1[1] = $col1[2] = $col1[0];
				}

				if (!isset($col2[1])) {
					$col2[1] = $col2[2] = $col2[0];
				}

				if (!isset($col3[1])) {
					$col3[1] = $col3[2] = $col3[0];
				}

				if (!isset($col4[1])) {
					$col4[1] = $col4[2] = $col4[0];
				}

				$patch_array[0]['f'] = 0;
				$patch_array[0]['points'] = $coords;
				$patch_array[0]['colors'][0]['r'] = $col1[0];
				$patch_array[0]['colors'][0]['g'] = $col1[1];
				$patch_array[0]['colors'][0]['b'] = $col1[2];
				$patch_array[0]['colors'][1]['r'] = $col2[0];
				$patch_array[0]['colors'][1]['g'] = $col2[1];
				$patch_array[0]['colors'][1]['b'] = $col2[2];
				$patch_array[0]['colors'][2]['r'] = $col3[0];
				$patch_array[0]['colors'][2]['g'] = $col3[1];
				$patch_array[0]['colors'][2]['b'] = $col3[2];
				$patch_array[0]['colors'][3]['r'] = $col4[0];
				$patch_array[0]['colors'][3]['g'] = $col4[1];
				$patch_array[0]['colors'][3]['b'] = $col4[2];
			}
			else {
				$patch_array = $coords;
			}

			$bpcd = 65535;
			$this->gradients[$n]['stream'] = '';
			$count_patch = count($patch_array);

			for ($i = 0; $i < $count_patch; ++$i) {
				$this->gradients[$n]['stream'] .= chr($patch_array[$i]['f']);
				$count_points = count($patch_array[$i]['points']);

				for ($j = 0; $j < $count_points; ++$j) {
					$patch_array[$i]['points'][$j] = (($patch_array[$i]['points'][$j] - $coords_min) / ($coords_max - $coords_min)) * $bpcd;

					if ($patch_array[$i]['points'][$j] < 0) {
						$patch_array[$i]['points'][$j] = 0;
					}

					if ($bpcd < $patch_array[$i]['points'][$j]) {
						$patch_array[$i]['points'][$j] = $bpcd;
					}

					$this->gradients[$n]['stream'] .= chr(floor($patch_array[$i]['points'][$j] / 256));
					$this->gradients[$n]['stream'] .= chr(floor($patch_array[$i]['points'][$j] % 256));
				}

				$count_cols = count($patch_array[$i]['colors']);

				for ($j = 0; $j < $count_cols; ++$j) {
					$this->gradients[$n]['stream'] .= chr($patch_array[$i]['colors'][$j]['r']);
					$this->gradients[$n]['stream'] .= chr($patch_array[$i]['colors'][$j]['g']);
					$this->gradients[$n]['stream'] .= chr($patch_array[$i]['colors'][$j]['b']);
				}
			}

			$this->_out('/Sh' . $n . ' sh');
			$this->_out('Q');
		}

		protected function Clip($x, $y, $w, $h)
		{
			if ($this->rtl) {
				$x = $this->w - $x - $w;
			}

			$s = 'q';
			$s .= sprintf(' %.2F %.2F %.2F %.2F re W n', $x * $this->k, ($this->h - $y) * $this->k, $w * $this->k, (0 - $h) * $this->k);
			$s .= sprintf(' %.3F 0 0 %.3F %.3F %.3F cm', $w * $this->k, $h * $this->k, $x * $this->k, ($this->h - ($y + $h)) * $this->k);
			$this->_out($s);
		}

		protected function Gradient($type, $col1, $col2, $coords)
		{
			$n = count($this->gradients) + 1;
			$this->gradients[$n]['type'] = $type;

			if (!isset($col1[1])) {
				$col1[1] = $col1[2] = $col1[0];
			}

			$this->gradients[$n]['col1'] = sprintf('%.3F %.3F %.3F', $col1[0] / 255, $col1[1] / 255, $col1[2] / 255);

			if (!isset($col2[1])) {
				$col2[1] = $col2[2] = $col2[0];
			}

			$this->gradients[$n]['col2'] = sprintf('%.3F %.3F %.3F', $col2[0] / 255, $col2[1] / 255, $col2[2] / 255);
			$this->gradients[$n]['coords'] = $coords;
			$this->_out('/Sh' . $n . ' sh');
			$this->_out('Q');
		}

		public function _putshaders()
		{
			foreach ($this->gradients as $id => $grad) {
				if (($grad['type'] == 2) || ($grad['type'] == 3)) {
					$this->_newobj();
					$this->_out('<<');
					$this->_out('/FunctionType 2');
					$this->_out('/Domain [0.0 1.0]');
					$this->_out('/C0 [' . $grad['col1'] . ']');
					$this->_out('/C1 [' . $grad['col2'] . ']');
					$this->_out('/N 1');
					$this->_out('>>');
					$this->_out('endobj');
					$f1 = $this->n;
				}

				$this->_newobj();
				$this->_out('<<');
				$this->_out('/ShadingType ' . $grad['type']);
				$this->_out('/ColorSpace /DeviceRGB');

				if ($grad['type'] == 2) {
					$this->_out(sprintf('/Coords [%.3F %.3F %.3F %.3F]', $grad['coords'][0], $grad['coords'][1], $grad['coords'][2], $grad['coords'][3]));
					$this->_out('/Function ' . $f1 . ' 0 R');
					$this->_out('/Extend [true true] ');
					$this->_out('>>');
				}
				else if ($grad['type'] == 3) {
					$this->_out(sprintf('/Coords [%.3F %.3F 0 %.3F %.3F %.3F]', $grad['coords'][0], $grad['coords'][1], $grad['coords'][2], $grad['coords'][3], $grad['coords'][4]));
					$this->_out('/Function ' . $f1 . ' 0 R');
					$this->_out('/Extend [true true] ');
					$this->_out('>>');
				}
				else if ($grad['type'] == 6) {
					$this->_out('/BitsPerCoordinate 16');
					$this->_out('/BitsPerComponent 8');
					$this->_out('/Decode[0 1 0 1 0 1 0 1 0 1]');
					$this->_out('/BitsPerFlag 8');
					$this->_out('/Length ' . strlen($grad['stream']));
					$this->_out('>>');
					$this->_putstream($grad['stream']);
				}

				$this->_out('endobj');
				$this->gradients[$id]['id'] = $this->n;
			}
		}

		protected function _outarc($x1, $y1, $x2, $y2, $x3, $y3)
		{
			$h = $this->h;
			$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
		}

		public function PieSector($xc, $yc, $r, $a, $b, $style = 'FD', $cw = true, $o = 90)
		{
			if ($this->rtl) {
				$xc = $this->w - $xc;
			}

			if ($cw) {
				$d = $b;
				$b = $o - $a;
				$a = $o - $d;
			}
			else {
				$b += $o;
				$a += $o;
			}

			$a = ($a % 360) + 360;
			$b = ($b % 360) + 360;

			if ($b < $a) {
				$b += 360;
			}

			$b = ($b / 360) * 2 * M_PI;
			$a = ($a / 360) * 2 * M_PI;
			$d = $b - $a;

			if ($d == 0) {
				$d = 2 * M_PI;
			}

			$k = $this->k;
			$hp = $this->h;

			if ($style == 'F') {
				$op = 'f';
			}
			else {
				if (($style == 'FD') || ($style == 'DF')) {
					$op = 'b';
				}
				else {
					$op = 's';
				}
			}

			if (sin($d / 2)) {
				$MyArc = (((4 / 3) * (1 - cos($d / 2))) / sin($d / 2)) * $r;
			}

			$this->_out(sprintf('%.2F %.2F m', $xc * $k, ($hp - $yc) * $k));
			$this->_out(sprintf('%.2F %.2F l', ($xc + ($r * cos($a))) * $k, ($hp - $yc - ($r * sin($a))) * $k));

			if ($d < (M_PI / 2)) {
				$this->_outarc($xc + ($r * cos($a)) + ($MyArc * cos((M_PI / 2) + $a)), $yc - ($r * sin($a)) - ($MyArc * sin((M_PI / 2) + $a)), $xc + ($r * cos($b)) + ($MyArc * cos($b - (M_PI / 2))), $yc - ($r * sin($b)) - ($MyArc * sin($b - (M_PI / 2))), $xc + ($r * cos($b)), $yc - ($r * sin($b)));
			}
			else {
				$b = $a + ($d / 4);
				$MyArc = (((4 / 3) * (1 - cos($d / 8))) / sin($d / 8)) * $r;
				$this->_outarc($xc + ($r * cos($a)) + ($MyArc * cos((M_PI / 2) + $a)), $yc - ($r * sin($a)) - ($MyArc * sin((M_PI / 2) + $a)), $xc + ($r * cos($b)) + ($MyArc * cos($b - (M_PI / 2))), $yc - ($r * sin($b)) - ($MyArc * sin($b - (M_PI / 2))), $xc + ($r * cos($b)), $yc - ($r * sin($b)));
				$a = $b;
				$b = $a + ($d / 4);
				$this->_outarc($xc + ($r * cos($a)) + ($MyArc * cos((M_PI / 2) + $a)), $yc - ($r * sin($a)) - ($MyArc * sin((M_PI / 2) + $a)), $xc + ($r * cos($b)) + ($MyArc * cos($b - (M_PI / 2))), $yc - ($r * sin($b)) - ($MyArc * sin($b - (M_PI / 2))), $xc + ($r * cos($b)), $yc - ($r * sin($b)));
				$a = $b;
				$b = $a + ($d / 4);
				$this->_outarc($xc + ($r * cos($a)) + ($MyArc * cos((M_PI / 2) + $a)), $yc - ($r * sin($a)) - ($MyArc * sin((M_PI / 2) + $a)), $xc + ($r * cos($b)) + ($MyArc * cos($b - (M_PI / 2))), $yc - ($r * sin($b)) - ($MyArc * sin($b - (M_PI / 2))), $xc + ($r * cos($b)), $yc - ($r * sin($b)));
				$a = $b;
				$b = $a + ($d / 4);
				$this->_outarc($xc + ($r * cos($a)) + ($MyArc * cos((M_PI / 2) + $a)), $yc - ($r * sin($a)) - ($MyArc * sin((M_PI / 2) + $a)), $xc + ($r * cos($b)) + ($MyArc * cos($b - (M_PI / 2))), $yc - ($r * sin($b)) - ($MyArc * sin($b - (M_PI / 2))), $xc + ($r * cos($b)), $yc - ($r * sin($b)));
			}

			$this->_out($op);
		}

		public function ImageEps($file, $x = '', $y = '', $w = 0, $h = 0, $link = '', $useBoundingBox = true, $align = '', $palign = '', $border = 0)
		{
			if ($x === '') {
				$x = $this->x;
			}

			if ($y === '') {
				$y = $this->y;
			}

			$k = $this->k;
			$data = file_get_contents($file);

			if ($data === false) {
				$this->Error('EPS file not found: ' . $file);
			}

			$regs = array();
			preg_match('/%%Creator:([^' . "\r\n" . ']+)/', $data, $regs);

			if (1 < count($regs)) {
				$version_str = trim($regs[1]);

				if (strpos($version_str, 'Adobe Illustrator') !== false) {
					$versexp = explode(' ', $version_str);
					$version = (double) array_pop($versexp);

					if (9 <= $version) {
						$this->Error('This version of Adobe Illustrator file is not supported: ' . $file);
					}
				}
			}

			$start = strpos($data, '%!PS-Adobe');

			if (0 < $start) {
				$data = substr($data, $start);
			}

			preg_match('/%%BoundingBox:([^' . "\r\n" . ']+)/', $data, $regs);

			if (1 < count($regs)) {
				list($x1, $y1, $x2, $y2) = explode(' ', trim($regs[1]));
			}
			else {
				$this->Error('No BoundingBox found in EPS file: ' . $file);
			}

			$start = strpos($data, '%%EndSetup');

			if ($start === false) {
				$start = strpos($data, '%%EndProlog');
			}

			if ($start === false) {
				$start = strpos($data, '%%BoundingBox');
			}

			$data = substr($data, $start);
			$end = strpos($data, '%%PageTrailer');

			if ($end === false) {
				$end = strpos($data, 'showpage');
			}

			if ($end) {
				$data = substr($data, 0, $end);
			}

			if (0 < $w) {
				$scale_x = $w / ($x2 - $x1) / $k;

				if (0 < $h) {
					$scale_y = $h / ($y2 - $y1) / $k;
				}
				else {
					$scale_y = $scale_x;
					$h = (($y2 - $y1) / $k) * $scale_y;
				}
			}
			else if (0 < $h) {
				$scale_y = $h / ($y2 - $y1) / $k;
				$scale_x = $scale_y;
				$w = (($x2 - $x1) / $k) * $scale_x;
			}
			else {
				$w = ($x2 - $x1) / $k;
				$h = ($y2 - $y1) / $k;
			}

			if ($this->checkPageBreak($h, $y)) {
				$y = $this->GetY() + $this->cMargin;
			}

			$this->img_rb_y = $y + $h;

			if ($this->rtl) {
				if ($palign == 'L') {
					$ximg = $this->lMargin;
					$this->img_rb_x = $ximg + $w;
				}
				else if ($palign == 'C') {
					$ximg = ($this->w - $x - $w) / 2;
					$this->img_rb_x = $ximg + $w;
				}
				else {
					$ximg = $this->w - $x - $w;
					$this->img_rb_x = $ximg;
				}
			}
			else if ($palign == 'R') {
				$ximg = $this->w - $this->rMargin - $w;
				$this->img_rb_x = $ximg;
			}
			else if ($palign == 'C') {
				$ximg = ($this->w - $x - $w) / 2;
				$this->img_rb_x = $ximg + $w;
			}
			else {
				$ximg = $x;
				$this->img_rb_x = $ximg + $w;
			}

			if ($useBoundingBox) {
				$dx = ($ximg * $k) - $x1;
				$dy = ($y * $k) - $y1;
			}
			else {
				$dx = $ximg * $k;
				$dy = $y * $k;
			}

			$this->_out('q' . $this->epsmarker);
			$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', 1, 0, 0, 1, $dx, $dy + ($this->hPt - (2 * $y * $k) - $y2 - $y1)));

			if (isset($scale_x)) {
				$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $scale_x, 0, 0, $scale_y, $x1 * (1 - $scale_x), $y2 * (1 - $scale_y)));
			}

			preg_match('/[\\r\\n]+/s', $data, $regs);
			$lines = explode($regs[0], $data);
			$u = 0;
			$cnt = count($lines);

			for ($i = 0; $i < $cnt; ++$i) {
				$line = $lines[$i];
				if (($line == '') || ($line[0] == '%')) {
					continue;
				}

				$len = strlen($line);
				$chunks = explode(' ', $line);
				$cmd = array_pop($chunks);
				if (($cmd == 'Xa') || ($cmd == 'XA')) {
					$b = array_pop($chunks);
					$g = array_pop($chunks);
					$r = array_pop($chunks);
					$this->_out('' . $r . ' ' . $g . ' ' . $b . ' ' . ($cmd == 'Xa' ? 'rg' : 'RG'));
					continue;
				}

				switch ($cmd) {
				case 'm':
				case 'l':
				case 'v':
				case 'y':
				case 'c':
				case 'k':
				case 'K':
				case 'g':
				case 'G':
				case 's':
				case 'S':
				case 'J':
				case 'j':
				case 'w':
				case 'M':
				case 'd':
				case 'n':
				case 'v':
					$this->_out($line);
					break;

				case 'x':
					list($c, $m, $y, $k) = $chunks;
					$this->_out('' . $c . ' ' . $m . ' ' . $y . ' ' . $k . ' k');
					break;

				case 'X':
					list($c, $m, $y, $k) = $chunks;
					$this->_out('' . $c . ' ' . $m . ' ' . $y . ' ' . $k . ' K');
					break;

				case 'Y':
				case 'N':
				case 'V':
				case 'L':
				case 'C':
					$line[$len - 1] = strtolower($cmd);
					$this->_out($line);
					break;

				case 'b':
				case 'B':
					$this->_out($cmd . '*');
					break;

				case 'f':
				case 'F':
					if (0 < $u) {
						$isU = false;
						$max = min($i + 5, $cnt);

						for ($j = $i + 1; $j < $max; ++$j) {
							$isU = $isU || ($lines[$j] == 'U') || ($lines[$j] == '*U');
						}

						if ($isU) {
							$this->_out('f*');
						}
					}
					else {
						$this->_out('f*');
					}

					break;

				case '*u':
					++$u;
					break;

				case '*U':
					--$u;
					break;
				}
			}

			$this->_out($this->epsmarker . 'Q');

			if (!empty($border)) {
				$bx = $x;
				$by = $y;
				$this->x = $x;
				$this->y = $y;
				$this->Cell($w, $h, '', $border, 0, '', 0, '', 0);
				$this->x = $bx;
				$this->y = $by;
			}

			if ($link) {
				$this->Link($ximg, $y, $w, $h, $link, 0);
			}

			switch ($align) {
			case 'T':
				$this->y = $y;
				$this->x = $this->img_rb_x;
				break;

			case 'M':
				$this->y = $y + round($h / 2);
				$this->x = $this->img_rb_x;
				break;

			case 'B':
				$this->y = $this->img_rb_y;
				$this->x = $this->img_rb_x;
				break;

			case 'N':
				$this->SetY($this->img_rb_y);
				break;

			default:
				break;
			}

			$this->endlinex = $this->img_rb_x;
		}

		public function setBarcode($bc = '')
		{
			$this->barcode = $bc;
		}

		public function getBarcode()
		{
			return $this->barcode;
		}

		public function write1DBarcode($code, $type, $x = '', $y = '', $w = '', $h = '', $xres = 0.4, $style = '', $align = '')
		{
			if ($this->empty_string($code)) {
				return NULL;
			}

			require_once dirname(__FILE__) . '/barcodes.php';
			$gvars = $this->getGraphicVars();
			$barcodeobj = new TCPDFBarcode($code, $type);
			$arrcode = $barcodeobj->getBarcodeArray();

			if ($arrcode === false) {
				$this->Error('Error in 1D barcode string');
			}

			if (!isset($style['position'])) {
				if ($this->rtl) {
					$style['position'] = 'R';
				}
				else {
					$style['position'] = 'L';
				}
			}

			if (!isset($style['padding'])) {
				$style['padding'] = 0;
			}

			if (!isset($style['fgcolor'])) {
				$style['fgcolor'] = array(0, 0, 0);
			}

			if (!isset($style['bgcolor'])) {
				$style['bgcolor'] = false;
			}

			if (!isset($style['border'])) {
				$style['border'] = false;
			}

			$fontsize = 0;

			if (!isset($style['text'])) {
				$style['text'] = false;
			}

			if ($style['text'] && isset($style['font'])) {
				if (isset($style['fontsize'])) {
					$fontsize = $style['fontsize'];
				}

				$this->SetFont($style['font'], '', $fontsize);
			}

			if (!isset($style['stretchtext'])) {
				$style['stretchtext'] = 4;
			}

			$this->SetDrawColorArray($style['fgcolor']);
			$this->SetTextColorArray($style['fgcolor']);
			if ($this->empty_string($w) || ($w <= 0)) {
				if ($this->rtl) {
					$w = $this->x - $this->lMargin;
				}
				else {
					$w = $this->w - $this->rMargin - $this->x;
				}
			}

			if ($this->empty_string($x)) {
				$x = $this->GetX();
			}

			if ($this->rtl) {
				$x = $this->w - $x;
			}

			if ($this->empty_string($y)) {
				$y = $this->GetY();
			}

			if ($this->empty_string($xres)) {
				$xres = 0.4;
			}

			$fbw = ($arrcode['maxw'] * $xres) + (2 * $style['padding']);
			$extraspace = (($this->cell_height_ratio * $fontsize) / $this->k) + (2 * $style['padding']);
			if ($this->empty_string($h) || ($h <= 0)) {
				$h = 10 + $extraspace;
			}

			if ($this->checkPageBreak($h)) {
				$y = $this->y;
			}

			$barh = $h - $extraspace;

			switch ($style['position']) {
			case 'L':
				if ($this->rtl) {
					$xpos = $x - $w;
				}
				else {
					$xpos = $x;
				}

				break;

			case 'C':
				$xdiff = ($w - $fbw) / 2;

				if ($this->rtl) {
					$xpos = ($x - $w) + $xdiff;
				}
				else {
					$xpos = $x + $xdiff;
				}

				break;

			case 'R':
				if ($this->rtl) {
					$xpos = $x - $fbw;
				}
				else {
					$xpos = ($x + $w) - $fbw;
				}

				break;

			case 'S':
				$fbw = $w;
				$xres = ($w - (2 * $style['padding'])) / $arrcode['maxw'];

				if ($this->rtl) {
					$xpos = $x - $w;
				}
				else {
					$xpos = $x;
				}

				break;
			}

			$xpos_rect = $xpos;
			$xpos = $xpos_rect + $style['padding'];
			$xpos_text = $xpos;
			$tempRTL = $this->rtl;
			$this->rtl = false;

			if ($style['bgcolor']) {
				$this->Rect($xpos_rect, $y, $fbw, $h, $style['border'] ? 'DF' : 'F', '', $style['bgcolor']);
			}
			else if ($style['border']) {
				$this->Rect($xpos_rect, $y, $fbw, $h, 'D');
			}

			if ($arrcode !== false) {
				foreach ($arrcode['bcode'] as $k => $v) {
					$bw = $v['w'] * $xres;

					if ($v['t']) {
						$ypos = $y + $style['padding'] + (($v['p'] * $barh) / $arrcode['maxh']);
						$this->Rect($xpos, $ypos, $bw, ($v['h'] * $barh) / $arrcode['maxh'], 'F', array(), $style['fgcolor']);
					}

					$xpos += $bw;
				}
			}

			if ($style['text']) {
				$this->x = $xpos_text;
				$this->y = $y + $style['padding'] + $barh;
				$this->Cell($arrcode['maxw'] * $xres, ($this->cell_height_ratio * $fontsize) / $this->k, $code, 0, 0, 'C', 0, '', $style['stretchtext']);
			}

			$this->rtl = $tempRTL;
			$this->setGraphicVars($gvars);
			$this->img_rb_y = $y + $h;

			if ($this->rtl) {
				$this->img_rb_x = $this->w - $x - $w;
			}
			else {
				$this->img_rb_x = $x + $w;
			}

			switch ($align) {
			case 'T':
				$this->y = $y;
				$this->x = $this->img_rb_x;
				break;

			case 'M':
				$this->y = $y + round($h / 2);
				$this->x = $this->img_rb_x;
				break;

			case 'B':
				$this->y = $this->img_rb_y;
				$this->x = $this->img_rb_x;
				break;

			case 'N':
				$this->SetY($this->img_rb_y);
				break;

			default:
				break;
			}
		}

		public function writeBarcode($x, $y, $w, $h, $type, $style, $font, $xres, $code)
		{
			$xres = 1 / $xres;
			$newstyle = array(
				'position'    => 'L',
				'border'      => false,
				'padding'     => 0,
				'fgcolor'     => array(0, 0, 0),
				'bgcolor'     => false,
				'text'        => true,
				'font'        => $font,
				'fontsize'    => 8,
				'stretchtext' => 4
				);

			if ($style & 1) {
				$newstyle['border'] = true;
			}

			if ($style & 2) {
				$newstyle['bgcolor'] = false;
			}

			if ($style & 4) {
				$newstyle['position'] = 'C';
			}
			else if ($style & 8) {
				$newstyle['position'] = 'L';
			}
			else if ($style & 16) {
				$newstyle['position'] = 'R';
			}

			if ($style & 128) {
				$newstyle['text'] = true;
			}

			if ($style & 256) {
				$newstyle['stretchtext'] = 4;
			}

			$this->write1DBarcode($code, $type, $x, $y, $w, $h, $xres, $newstyle, '');
		}

		public function write2DBarcode($code, $type, $x = '', $y = '', $w = '', $h = '', $style = '', $align = '')
		{
			if ($this->empty_string($code)) {
				return NULL;
			}

			require_once dirname(__FILE__) . '/2dbarcodes.php';
			$gvars = $this->getGraphicVars();
			$barcodeobj = new TCPDF2DBarcode($code, $type);
			$arrcode = $barcodeobj->getBarcodeArray();

			if ($arrcode === false) {
				$this->Error('Error in 2D barcode string');
			}

			if (!isset($style['padding'])) {
				$style['padding'] = 0;
			}

			if (!isset($style['fgcolor'])) {
				$style['fgcolor'] = array(0, 0, 0);
			}

			if (!isset($style['bgcolor'])) {
				$style['bgcolor'] = false;
			}

			if (!isset($style['border'])) {
				$style['border'] = false;
			}

			$this->SetDrawColorArray($style['fgcolor']);

			if ($this->empty_string($x)) {
				$x = $this->GetX();
			}

			if ($this->rtl) {
				$x = $this->w - $x;
			}

			if ($this->empty_string($y)) {
				$y = $this->GetY();
			}

			if ($this->empty_string($w) || ($w <= 0)) {
				if ($this->rtl) {
					$w = $x - $this->lMargin;
				}
				else {
					$w = $this->w - $this->rMargin - $x;
				}
			}

			if ($this->empty_string($h) || ($h <= 0)) {
				$h = $w;
			}

			if ($this->checkPageBreak($h)) {
				$y = $this->y;
			}

			$bw = $w - (2 * $style['padding']);
			$bh = $h - (2 * $style['padding']);

			if ($this->rtl) {
				$xpos = $x - $w;
			}
			else {
				$xpos = $x;
			}

			$xpos += $style['padding'];
			$ypos = $y + $style['padding'];
			$tempRTL = $this->rtl;
			$this->rtl = false;

			if ($style['bgcolor']) {
				$this->Rect($x, $y, $w, $h, $style['border'] ? 'DF' : 'F', '', $style['bgcolor']);
			}
			else if ($style['border']) {
				$this->Rect($x, $y, $w, $h, 'D');
			}

			if ($arrcode !== false) {
				$rows = $arrcode['num_rows'];
				$cols = $arrcode['num_cols'];
				$cw = $bw / $cols;
				$ch = $bh / $rows;

				for ($r = 0; $r < $rows; ++$r) {
					$xr = $xpos;

					for ($c = 0; $c < $cols; ++$c) {
						if ($arrcode['bcode'][$r][$c] == 1) {
							$this->Rect($xr, $ypos, $cw, $ch, 'F', array(), $style['fgcolor']);
						}

						$xr += $cw;
					}

					$ypos += $ch;
				}
			}

			$this->rtl = $tempRTL;
			$this->setGraphicVars($gvars);
			$this->img_rb_y = $y + $h;

			if ($this->rtl) {
				$this->img_rb_x = $this->w - $x - $w;
			}
			else {
				$this->img_rb_x = $x + $w;
			}

			switch ($align) {
			case 'T':
				$this->y = $y;
				$this->x = $this->img_rb_x;
				break;

			case 'M':
				$this->y = $y + round($h / 2);
				$this->x = $this->img_rb_x;
				break;

			case 'B':
				$this->y = $this->img_rb_y;
				$this->x = $this->img_rb_x;
				break;

			case 'N':
				$this->SetY($this->img_rb_y);
				break;

			default:
				break;
			}
		}

		public function getMargins()
		{
			$ret = array('left' => $this->lMargin, 'right' => $this->rMargin, 'top' => $this->tMargin, 'bottom' => $this->bMargin, 'header' => $this->header_margin, 'footer' => $this->footer_margin, 'cell' => $this->cMargin);
			return $ret;
		}

		public function getOriginalMargins()
		{
			$ret = array('left' => $this->original_lMargin, 'right' => $this->original_rMargin);
			return $ret;
		}

		public function getFontSize()
		{
			return $this->FontSize;
		}

		public function getFontSizePt()
		{
			return $this->FontSizePt;
		}

		public function getFontFamily()
		{
			return $this->FontFamily;
		}

		public function getFontStyle()
		{
			return $this->FontStyle;
		}

		public function writeHTMLCell($w, $h, $x, $y, $html = '', $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true)
		{
			return $this->MultiCell($w, $h, $html, $border, $align, $fill, $ln, $x, $y, $reseth, 0, true, $autopadding, 0);
		}

		protected function getHtmlDomArray($html)
		{
			$html = strip_tags($html, '<marker/><a><b><blockquote><br><br/><dd><del><div><dl><dt><em><font><form><h1><h2><h3><h4><h5><h6><hr><i><img><input><label><li><ol><option><p><pre><select><small><span><strong><sub><sup><table><tablehead><tcpdf><td><textarea><th><thead><tr><tt><u><ul>');
			$html = preg_replace('/<pre/', '<xre', $html);
			$html = preg_replace('/<(table|tr|td|th|tcpdf|blockquote|dd|div|dt|form|h1|h2|h3|h4|h5|h6|br|hr|li|ol|ul|p)([^\\>]*)>[\\n\\r\\t]+/', '<\\1\\2>', $html);
			$html = preg_replace('@(\\r\\n|\\r)@', "\n", $html);
			$repTable = array('	' => ' ', '' . "\0" . '' => ' ', '' => ' ', '\\' => '\\\\');
			$html = strtr($html, $repTable);
			$offset = 0;

			while (($offset < strlen($html)) && (($pos = strpos($html, '</pre>', $offset)) !== false)) {
				$html_a = substr($html, 0, $offset);
				$html_b = substr($html, $offset, ($pos - $offset) + 6);

				while (preg_match('\'<xre([^\\>]*)>(.*?)' . "\n" . '(.*?)</pre>\'si', $html_b)) {
					$html_b = preg_replace('\'<xre([^\\>]*)>(.*?)' . "\n" . '(.*?)</pre>\'si', '<xre\\1>\\2<br />\\3</pre>', $html_b);
				}

				$html = $html_a . $html_b . substr($html, $pos + 6);
				$offset = strlen($html_a . $html_b);
			}

			$offset = 0;

			while (($offset < strlen($html)) && (($pos = strpos($html, '</textarea>', $offset)) !== false)) {
				$html_a = substr($html, 0, $offset);
				$html_b = substr($html, $offset, ($pos - $offset) + 11);

				while (preg_match('\'<textarea([^\\>]*)>(.*?)' . "\n" . '(.*?)</textarea>\'si', $html_b)) {
					$html_b = preg_replace('\'<textarea([^\\>]*)>(.*?)' . "\n" . '(.*?)</textarea>\'si', '<textarea\\1>\\2<TBR>\\3</textarea>', $html_b);
					$html_b = preg_replace('\'<textarea([^\\>]*)>(.*?)["](.*?)</textarea>\'si', '<textarea\\1>\\2\'\'\\3</textarea>', $html_b);
				}

				$html = $html_a . $html_b . substr($html, $pos + 11);
				$offset = strlen($html_a . $html_b);
			}

			$html = preg_replace('\'([\\s]*)<option\'si', '<option', $html);
			$html = preg_replace('\'</option>([\\s]*)\'si', '</option>', $html);
			$offset = 0;

			while (($offset < strlen($html)) && (($pos = strpos($html, '</option>', $offset)) !== false)) {
				$html_a = substr($html, 0, $offset);
				$html_b = substr($html, $offset, ($pos - $offset) + 9);

				while (preg_match('\'<option([^\\>]*)>(.*?)</option>\'si', $html_b)) {
					$html_b = preg_replace('\'<option([\\s]+)value="([^"]*)"([^\\>]*)>(.*?)</option>\'si', '\\2	\\4' . "\r" . '', $html_b);
					$html_b = preg_replace('\'<option([^\\>]*)>(.*?)</option>\'si', '\\2' . "\r" . '', $html_b);
				}

				$html = $html_a . $html_b . substr($html, $pos + 9);
				$offset = strlen($html_a . $html_b);
			}

			$html = preg_replace('\'<select([^\\>]*)>\'si', '<select\\1 opt="', $html);
			$html = preg_replace('\'([\\s]+)</select>\'si', '" />', $html);
			$html = str_replace("\n", ' ', $html);
			$html = str_replace('<TBR>', "\n", $html);
			$html = preg_replace('/[\\s]+<\\/(table|tr|td|th|ul|ol|li)>/', '</\\1>', $html);
			$html = preg_replace('/[\\s]+<(tr|td|th|ul|ol|li|br)/', '<\\1', $html);
			$html = preg_replace('/<\\/(table|tr|td|th|blockquote|dd|div|dt|h1|h2|h3|h4|h5|h6|hr|li|ol|ul|p)>[\\s]+</', '</\\1><', $html);
			$html = preg_replace('/<\\/(td|th)>/', '<marker style="font-size:0"/></\\1>', $html);
			$html = preg_replace('/<\\/table>([\\s]*)<marker style="font-size:0"\\/>/', '</table>', $html);
			$html = preg_replace('/<img/', ' <img', $html);
			$html = preg_replace('/<img([^\\>]*)>/xi', '<img\\1><span></span>', $html);
			$html = preg_replace('/<xre/', '<pre', $html);
			$html = preg_replace('/<textarea([^\\>]*)>/xi', '<textarea\\1 value="', $html);
			$html = preg_replace('/<\\/textarea>/', '" />', $html);
			$html = preg_replace('/^[\\s]+/', '', $html);
			$html = preg_replace('/[\\s]+$/', '', $html);
			$tagpattern = '/(<[^>]+>)/';
			$a = preg_split($tagpattern, $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			$maxel = count($a);
			$elkey = 0;
			$key = 0;
			$dom = array();
			$dom[$key] = array();
			$dom[$key]['tag'] = false;
			$dom[$key]['value'] = '';
			$dom[$key]['parent'] = 0;
			$dom[$key]['fontname'] = $this->FontFamily;
			$dom[$key]['fontstyle'] = $this->FontStyle;
			$dom[$key]['fontsize'] = $this->FontSizePt;
			$dom[$key]['bgcolor'] = false;
			$dom[$key]['fgcolor'] = $this->fgcolor;
			$dom[$key]['align'] = '';
			$dom[$key]['listtype'] = '';
			$dom[$key]['text-indent'] = 0;
			$thead = false;
			++$key;
			$level = array();
			array_push($level, 0);

			while ($elkey < $maxel) {
				$dom[$key] = array();
				$element = $a[$elkey];
				$dom[$key]['elkey'] = $elkey;

				if (preg_match($tagpattern, $element)) {
					$element = substr($element, 1, -1);
					preg_match('/[\\/]?([a-zA-Z0-9]*)/', $element, $tag);
					$tagname = strtolower($tag[1]);

					if ($tagname == 'thead') {
						if ($element[0] == '/') {
							$thead = false;
						}
						else {
							$thead = true;
						}

						++$elkey;
						continue;
					}

					$dom[$key]['tag'] = true;
					$dom[$key]['value'] = $tagname;

					if ($element[0] == '/') {
						$dom[$key]['opening'] = false;
						$dom[$key]['parent'] = end($level);
						array_pop($level);
						$dom[$key]['fontname'] = $dom[$dom[$dom[$key]['parent']]['parent']]['fontname'];
						$dom[$key]['fontstyle'] = $dom[$dom[$dom[$key]['parent']]['parent']]['fontstyle'];
						$dom[$key]['fontsize'] = $dom[$dom[$dom[$key]['parent']]['parent']]['fontsize'];
						$dom[$key]['bgcolor'] = $dom[$dom[$dom[$key]['parent']]['parent']]['bgcolor'];
						$dom[$key]['fgcolor'] = $dom[$dom[$dom[$key]['parent']]['parent']]['fgcolor'];
						$dom[$key]['align'] = $dom[$dom[$dom[$key]['parent']]['parent']]['align'];

						if (isset($dom[$dom[$dom[$key]['parent']]['parent']]['listtype'])) {
							$dom[$key]['listtype'] = $dom[$dom[$dom[$key]['parent']]['parent']]['listtype'];
						}

						if (($dom[$key]['value'] == 'tr') && !isset($dom[$dom[$dom[$key]['parent']]['parent']]['cols'])) {
							$dom[$dom[$dom[$key]['parent']]['parent']]['cols'] = $dom[$dom[$key]['parent']]['cols'];
						}

						if (($dom[$key]['value'] == 'td') || ($dom[$key]['value'] == 'th')) {
							$dom[$dom[$key]['parent']]['content'] = '';

							for ($i = $dom[$key]['parent'] + 1; $i < $key; ++$i) {
								$dom[$dom[$key]['parent']]['content'] .= $a[$dom[$i]['elkey']];
							}

							$key = $i;
						}

						if (($dom[$key]['value'] == 'tr') && ($dom[$dom[$key]['parent']]['thead'] === true)) {
							if ($this->empty_string($dom[$dom[$dom[$key]['parent']]['parent']]['thead'])) {
								$dom[$dom[$dom[$key]['parent']]['parent']]['thead'] = $a[$dom[$dom[$dom[$key]['parent']]['parent']]['elkey']];
							}

							for ($i = $dom[$key]['parent']; $i <= $key; ++$i) {
								$dom[$dom[$dom[$key]['parent']]['parent']]['thead'] .= $a[$dom[$i]['elkey']];
							}
						}

						if (($dom[$key]['value'] == 'table') && !$this->empty_string($dom[$dom[$key]['parent']]['thead'])) {
							$dom[$dom[$key]['parent']]['thead'] .= '</tablehead>';
						}
					}
					else {
						$dom[$key]['opening'] = true;
						$dom[$key]['parent'] = end($level);

						if (substr($element, -1, 1) != '/') {
							array_push($level, $key);
							$dom[$key]['self'] = false;
						}
						else {
							$dom[$key]['self'] = true;
						}

						$parentkey = 0;

						if (0 < $key) {
							$parentkey = $dom[$key]['parent'];
							$dom[$key]['fontname'] = $dom[$parentkey]['fontname'];
							$dom[$key]['fontstyle'] = $dom[$parentkey]['fontstyle'];
							$dom[$key]['fontsize'] = $dom[$parentkey]['fontsize'];
							$dom[$key]['bgcolor'] = $dom[$parentkey]['bgcolor'];
							$dom[$key]['fgcolor'] = $dom[$parentkey]['fgcolor'];
							$dom[$key]['align'] = $dom[$parentkey]['align'];
							$dom[$key]['listtype'] = $dom[$parentkey]['listtype'];
							$dom[$key]['text-indent'] = $dom[$parentkey]['text-indent'];
						}

						preg_match_all('/([^=\\s]*)=["]?([^"]*)["]?/', $element, $attr_array, PREG_PATTERN_ORDER);
						$dom[$key]['attribute'] = array();

						while (list($id, $name) = each($attr_array[1])) {
							$dom[$key]['attribute'][strtolower($name)] = $attr_array[2][$id];
						}

						if (isset($dom[$key]['attribute']['style'])) {
							preg_match_all('/([^;:\\s]*):([^;]*)/', $dom[$key]['attribute']['style'], $style_array, PREG_PATTERN_ORDER);
							$dom[$key]['style'] = array();

							while (list($id, $name) = each($style_array[1])) {
								$dom[$key]['style'][strtolower($name)] = trim($style_array[2][$id]);
							}

							if (isset($dom[$key]['style']['font-family'])) {
								if (isset($dom[$key]['style']['font-family'])) {
									$fontslist = preg_split('/[,]/', strtolower($dom[$key]['style']['font-family']));

									foreach ($fontslist as $font) {
										$font = trim(strtolower($font));
										if (in_array($font, $this->fontlist) || in_array($font, $this->fontkeys)) {
											$dom[$key]['fontname'] = $font;
											break;
										}
									}
								}
							}

							if (isset($dom[$key]['style']['list-style-type'])) {
								$dom[$key]['listtype'] = trim(strtolower($dom[$key]['style']['list-style-type']));

								if ($dom[$key]['listtype'] == 'inherit') {
									$dom[$key]['listtype'] = $dom[$parentkey]['listtype'];
								}
							}

							if (isset($dom[$key]['style']['text-indent'])) {
								$dom[$key]['text-indent'] = $this->getHTMLUnitToUnits($dom[$key]['style']['text-indent']);

								if ($dom[$key]['text-indent'] == 'inherit') {
									$dom[$key]['text-indent'] = $dom[$parentkey]['text-indent'];
								}
							}

							if (isset($dom[$key]['style']['font-size'])) {
								$fsize = trim($dom[$key]['style']['font-size']);

								switch ($fsize) {
								case 'xx-small':
									$dom[$key]['fontsize'] = $dom[0]['fontsize'] - 4;
									break;

								case 'x-small':
									$dom[$key]['fontsize'] = $dom[0]['fontsize'] - 3;
									break;

								case 'small':
									$dom[$key]['fontsize'] = $dom[0]['fontsize'] - 2;
									break;

								case 'medium':
									$dom[$key]['fontsize'] = $dom[0]['fontsize'];
									break;

								case 'large':
									$dom[$key]['fontsize'] = $dom[0]['fontsize'] + 2;
									break;

								case 'x-large':
									$dom[$key]['fontsize'] = $dom[0]['fontsize'] + 4;
									break;

								case 'xx-large':
									$dom[$key]['fontsize'] = $dom[0]['fontsize'] + 6;
									break;

								case 'smaller':
									$dom[$key]['fontsize'] = $dom[$parentkey]['fontsize'] - 3;
									break;

								case 'larger':
									$dom[$key]['fontsize'] = $dom[$parentkey]['fontsize'] + 3;
									break;

								default:
									$dom[$key]['fontsize'] = $this->getHTMLUnitToUnits($fsize, $dom[$parentkey]['fontsize'], 'pt', true);
								}
							}

							if (isset($dom[$key]['style']['font-weight']) && (strtolower($dom[$key]['style']['font-weight'][0]) == 'b')) {
								$dom[$key]['fontstyle'] .= 'B';
							}

							if (isset($dom[$key]['style']['font-style']) && (strtolower($dom[$key]['style']['font-style'][0]) == 'i')) {
								$dom[$key]['fontstyle'] .= '"I';
							}

							if (isset($dom[$key]['style']['color']) && !$this->empty_string($dom[$key]['style']['color'])) {
								$dom[$key]['fgcolor'] = $this->convertHTMLColorToDec($dom[$key]['style']['color']);
							}

							if (isset($dom[$key]['style']['background-color']) && !$this->empty_string($dom[$key]['style']['background-color'])) {
								$dom[$key]['bgcolor'] = $this->convertHTMLColorToDec($dom[$key]['style']['background-color']);
							}

							if (isset($dom[$key]['style']['text-decoration'])) {
								$decors = explode(' ', strtolower($dom[$key]['style']['text-decoration']));

								foreach ($decors as $dec) {
									$dec = trim($dec);

									if (!$this->empty_string($dec)) {
										if ($dec[0] == 'u') {
											$dom[$key]['fontstyle'] .= 'U';
										}
										else if ($dec[0] == 'l') {
											$dom[$key]['fontstyle'] .= 'D';
										}
									}
								}
							}

							if (isset($dom[$key]['style']['width'])) {
								$dom[$key]['width'] = $dom[$key]['style']['width'];
							}

							if (isset($dom[$key]['style']['height'])) {
								$dom[$key]['height'] = $dom[$key]['style']['height'];
							}

							if (isset($dom[$key]['style']['text-align'])) {
								$dom[$key]['align'] = strtoupper($dom[$key]['style']['text-align'][0]);
							}

							if (isset($dom[$key]['style']['border'])) {
								$dom[$key]['attribute']['border'] = $dom[$key]['style']['border'];
							}
						}

						if ($dom[$key]['value'] == 'font') {
							if (isset($dom[$key]['attribute']['face'])) {
								$fontslist = preg_split('/[,]/', strtolower($dom[$key]['attribute']['face']));

								foreach ($fontslist as $font) {
									$font = trim(strtolower($font));
									if (in_array($font, $this->fontlist) || in_array($font, $this->fontkeys)) {
										$dom[$key]['fontname'] = $font;
										break;
									}
								}
							}

							if (isset($dom[$key]['attribute']['size'])) {
								if (0 < $key) {
									if ($dom[$key]['attribute']['size'][0] == '+') {
										$dom[$key]['fontsize'] = $dom[$dom[$key]['parent']]['fontsize'] + intval(substr($dom[$key]['attribute']['size'], 1));
									}
									else if ($dom[$key]['attribute']['size'][0] == '-') {
										$dom[$key]['fontsize'] = $dom[$dom[$key]['parent']]['fontsize'] - intval(substr($dom[$key]['attribute']['size'], 1));
									}
									else {
										$dom[$key]['fontsize'] = intval($dom[$key]['attribute']['size']);
									}
								}
								else {
									$dom[$key]['fontsize'] = intval($dom[$key]['attribute']['size']);
								}
							}
						}

						if ((($dom[$key]['value'] == 'ul') || ($dom[$key]['value'] == 'ol') || ($dom[$key]['value'] == 'dl')) && (!isset($dom[$key]['align']) || $this->empty_string($dom[$key]['align']) || ($dom[$key]['align'] != 'J'))) {
							if ($this->rtl) {
								$dom[$key]['align'] = 'R';
							}
							else {
								$dom[$key]['align'] = 'L';
							}
						}

						if (($dom[$key]['value'] == 'small') || ($dom[$key]['value'] == 'sup') || ($dom[$key]['value'] == 'sub')) {
							$dom[$key]['fontsize'] = $dom[$key]['fontsize'] * K_SMALL_RATIO;
						}

						if (($dom[$key]['value'] == 'strong') || ($dom[$key]['value'] == 'b')) {
							$dom[$key]['fontstyle'] .= 'B';
						}

						if (($dom[$key]['value'] == 'em') || ($dom[$key]['value'] == 'i')) {
							$dom[$key]['fontstyle'] .= 'I';
						}

						if ($dom[$key]['value'] == 'u') {
							$dom[$key]['fontstyle'] .= 'U';
						}

						if ($dom[$key]['value'] == 'del') {
							$dom[$key]['fontstyle'] .= 'D';
						}

						if (($dom[$key]['value'] == 'pre') || ($dom[$key]['value'] == 'tt')) {
							$dom[$key]['fontname'] = $this->default_monospaced_font;
						}

						if (($dom[$key]['value'][0] == 'h') && (0 < intval($dom[$key]['value'][1])) && (intval($dom[$key]['value'][1]) < 7)) {
							$headsize = (4 - intval($dom[$key]['value'][1])) * 2;
							$dom[$key]['fontsize'] = $dom[0]['fontsize'] + $headsize;
							$dom[$key]['fontstyle'] .= 'B';
						}

						if ($dom[$key]['value'] == 'table') {
							$dom[$key]['rows'] = 0;
							$dom[$key]['trids'] = array();
							$dom[$key]['thead'] = '';
						}

						if ($dom[$key]['value'] == 'tr') {
							$dom[$key]['cols'] = 0;
							++$dom[$dom[$key]['parent']]['rows'];
							array_push($dom[$dom[$key]['parent']]['trids'], $key);

							if ($thead) {
								$dom[$key]['thead'] = true;
							}
							else {
								$dom[$key]['thead'] = false;
							}
						}

						if (($dom[$key]['value'] == 'th') || ($dom[$key]['value'] == 'td')) {
							if (isset($dom[$key]['attribute']['colspan'])) {
								$colspan = intval($dom[$key]['attribute']['colspan']);
							}
							else {
								$colspan = 1;
							}

							$dom[$key]['attribute']['colspan'] = $colspan;
							$dom[$dom[$key]['parent']]['cols'] += $colspan;
						}

						if (isset($dom[$key]['attribute']['color']) && !$this->empty_string($dom[$key]['attribute']['color'])) {
							$dom[$key]['fgcolor'] = $this->convertHTMLColorToDec($dom[$key]['attribute']['color']);
						}

						if (isset($dom[$key]['attribute']['bgcolor']) && !$this->empty_string($dom[$key]['attribute']['bgcolor'])) {
							$dom[$key]['bgcolor'] = $this->convertHTMLColorToDec($dom[$key]['attribute']['bgcolor']);
						}

						if (isset($dom[$key]['attribute']['width'])) {
							$dom[$key]['width'] = $dom[$key]['attribute']['width'];
						}

						if (isset($dom[$key]['attribute']['height'])) {
							$dom[$key]['height'] = $dom[$key]['attribute']['height'];
						}

						if (isset($dom[$key]['attribute']['align']) && !$this->empty_string($dom[$key]['attribute']['align']) && ($dom[$key]['value'] !== 'img')) {
							$dom[$key]['align'] = strtoupper($dom[$key]['attribute']['align'][0]);
						}
					}
				}
				else {
					$dom[$key]['tag'] = false;
					$dom[$key]['value'] = stripslashes($this->unhtmlentities($element));
					$dom[$key]['parent'] = end($level);
				}

				++$elkey;
				++$key;
			}

			return $dom;
		}

		public function writeHTML($html, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '')
		{
			$gvars = $this->getGraphicVars();
			$prevPage = $this->page;
			$prevlMargin = $this->lMargin;
			$prevrMargin = $this->rMargin;
			$curfontname = $this->FontFamily;
			$curfontstyle = $this->FontStyle;
			$curfontsize = $this->FontSizePt;
			$this->newline = true;
			$startlinepage = $this->page;
			$minstartliney = $this->y;
			$startlinex = $this->x;
			$startliney = $this->y;
			$yshift = 0;
			$newline = true;
			$loop = 0;
			$curpos = 0;
			$this_method_vars = array();
			$undo = false;
			$blocktags = array('blockquote', 'br', 'dd', 'div', 'dt', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'li', 'ol', 'p', 'ul', 'tcpdf');
			$this->premode = false;

			if (isset($this->PageAnnots[$this->page])) {
				$pask = count($this->PageAnnots[$this->page]);
			}
			else {
				$pask = 0;
			}

			if (isset($this->footerlen[$this->page])) {
				$this->footerpos[$this->page] = $this->pagelen[$this->page] - $this->footerlen[$this->page];
			}
			else {
				$this->footerpos[$this->page] = $this->pagelen[$this->page];
			}

			$startlinepos = $this->footerpos[$this->page];
			$lalign = $align;
			$plalign = $align;

			if ($this->rtl) {
				$w = $this->x - $this->lMargin;
			}
			else {
				$w = $this->w - $this->rMargin - $this->x;
			}

			$w -= 2 * $this->cMargin;

			if ($cell) {
				if ($this->rtl) {
					$this->x -= $this->cMargin;
				}
				else {
					$this->x += $this->cMargin;
				}
			}

			if (0 <= $this->customlistindent) {
				$this->listindent = $this->customlistindent;
			}
			else {
				$this->listindent = $this->GetStringWidth('0000');
			}

			$prev_listnum = $this->listnum;
			$prev_listordered = $this->listordered;
			$prev_listcount = $this->listcount;
			$prev_lispacer = $this->lispacer;
			$this->listnum = 0;
			$this->listordered = array();
			$this->listcount = array();
			$this->lispacer = '';
			if ($this->empty_string($this->lasth) || $reseth) {
				$this->lasth = $this->FontSize * $this->cell_height_ratio;
			}

			$dom = $this->getHtmlDomArray($html);
			$maxel = count($dom);
			$key = 0;

			while ($key < $maxel) {
				if ($dom[$key]['tag'] && $dom[$key]['opening'] && isset($dom[$key]['attribute']['nobr']) && ($dom[$key]['attribute']['nobr'] == 'true')) {
					if (isset($dom[$dom[$key]['parent']]['attribute']['nobr']) && ($dom[$dom[$key]['parent']]['attribute']['nobr'] == 'true')) {
						$dom[$key]['attribute']['nobr'] = false;
					}
					else {
						$this->startTransaction();
						$this_method_vars['html'] = $html;
						$this_method_vars['ln'] = $ln;
						$this_method_vars['fill'] = $fill;
						$this_method_vars['reseth'] = $reseth;
						$this_method_vars['cell'] = $cell;
						$this_method_vars['align'] = $align;
						$this_method_vars['gvars'] = $gvars;
						$this_method_vars['prevPage'] = $prevPage;
						$this_method_vars['prevlMargin'] = $prevlMargin;
						$this_method_vars['prevrMargin'] = $prevrMargin;
						$this_method_vars['curfontname'] = $curfontname;
						$this_method_vars['curfontstyle'] = $curfontstyle;
						$this_method_vars['curfontsize'] = $curfontsize;
						$this_method_vars['minstartliney'] = $minstartliney;
						$this_method_vars['yshift'] = $yshift;
						$this_method_vars['startlinepage'] = $startlinepage;
						$this_method_vars['startlinepos'] = $startlinepos;
						$this_method_vars['startlinex'] = $startlinex;
						$this_method_vars['startliney'] = $startliney;
						$this_method_vars['newline'] = $newline;
						$this_method_vars['loop'] = $loop;
						$this_method_vars['curpos'] = $curpos;
						$this_method_vars['pask'] = $pask;
						$this_method_vars['lalign'] = $lalign;
						$this_method_vars['plalign'] = $plalign;
						$this_method_vars['w'] = $w;
						$this_method_vars['prev_listnum'] = $prev_listnum;
						$this_method_vars['prev_listordered'] = $prev_listordered;
						$this_method_vars['prev_listcount'] = $prev_listcount;
						$this_method_vars['prev_lispacer'] = $prev_lispacer;
						$this_method_vars['key'] = $key;
						$this_method_vars['dom'] = $dom;
					}
				}

				if ($dom[$key]['tag'] || ($key == 0)) {
					if ((($dom[$key]['value'] == 'table') || ($dom[$key]['value'] == 'tr')) && isset($dom[$key]['align'])) {
						$dom[$key]['align'] = $this->rtl ? 'R' : 'L';
					}

					if (!$this->newline && ($dom[$key]['value'] == 'img') && isset($dom[$key]['attribute']['height']) && (0 < $dom[$key]['attribute']['height'])) {
						$imgh = $this->getHTMLUnitToUnits($dom[$key]['attribute']['height'], $this->lasth, 'px');

						if (!$this->InFooter) {
							$this->checkPageBreak($imgh);
						}

						if ($startlinepage < $this->page) {
							if (isset($this->footerlen[$startlinepage])) {
								$curpos = $this->pagelen[$startlinepage] - $this->footerlen[$startlinepage];
							}

							$pagebuff = $this->getPageBuffer($startlinepage);
							$linebeg = substr($pagebuff, $startlinepos, $curpos - $startlinepos);
							$tstart = substr($pagebuff, 0, $startlinepos);
							$tend = substr($this->getPageBuffer($startlinepage), $curpos);
							$this->setPageBuffer($startlinepage, $tstart . '' . $tend);
							$pagebuff = $this->getPageBuffer($this->page);
							$tstart = substr($pagebuff, 0, $this->cntmrk[$this->page]);
							$tend = substr($pagebuff, $this->cntmrk[$this->page]);
							$yshift = $minstartliney - $this->y;
							$try = sprintf('1 0 0 1 0 %.3F cm', $yshift * $this->k);
							$this->setPageBuffer($this->page, $tstart . '' . "\n" . 'q' . "\n" . '' . $try . "\n" . $linebeg . '' . "\n" . 'Q' . "\n" . '' . $tend);

							if (isset($this->PageAnnots[$this->page])) {
								$next_pask = count($this->PageAnnots[$this->page]);
							}
							else {
								$next_pask = 0;
							}

							if (isset($this->PageAnnots[$startlinepage])) {
								foreach ($this->PageAnnots[$startlinepage] as $pak => $pac) {
									if ($pask <= $pak) {
										$this->PageAnnots[$this->page][] = $pac;
										unset($this->PageAnnots[$startlinepage][$pak]);
										$npak = count($this->PageAnnots[$this->page]) - 1;
										$this->PageAnnots[$this->page][$npak]['y'] -= $yshift;
									}
								}
							}

							$pask = $next_pask;
							$startlinepos = $this->cntmrk[$this->page];
							$startlinepage = $this->page;
							$startliney = $this->y;
						}

						$this->y += ($curfontsize / $this->k) - $imgh;
						$minstartliney = min($this->y, $minstartliney);
					}
					else {
						if (isset($dom[$key]['fontname']) || isset($dom[$key]['fontstyle']) || isset($dom[$key]['fontsize'])) {
							$pfontname = $curfontname;
							$pfontstyle = $curfontstyle;
							$pfontsize = $curfontsize;
							$fontname = (isset($dom[$key]['fontname']) ? $dom[$key]['fontname'] : $curfontname);
							$fontstyle = (isset($dom[$key]['fontstyle']) ? $dom[$key]['fontstyle'] : $curfontstyle);
							$fontsize = (isset($dom[$key]['fontsize']) ? $dom[$key]['fontsize'] : $curfontsize);
							if (($fontname != $curfontname) || ($fontstyle != $curfontstyle) || ($fontsize != $curfontsize)) {
								$this->SetFont($fontname, $fontstyle, $fontsize);
								$this->lasth = $this->FontSize * $this->cell_height_ratio;
								if (is_numeric($fontsize) && (0 < $fontsize) && is_numeric($curfontsize) && (0 < $curfontsize) && ($fontsize != $curfontsize) && !$this->newline && ($key < ($maxel - 1))) {
									if (!$this->newline && ($startlinepage < $this->page)) {
										if (isset($this->footerlen[$startlinepage])) {
											$curpos = $this->pagelen[$startlinepage] - $this->footerlen[$startlinepage];
										}

										$pagebuff = $this->getPageBuffer($startlinepage);
										$linebeg = substr($pagebuff, $startlinepos, $curpos - $startlinepos);
										$tstart = substr($pagebuff, 0, $startlinepos);
										$tend = substr($this->getPageBuffer($startlinepage), $curpos);
										$this->setPageBuffer($startlinepage, $tstart . '' . $tend);
										$pagebuff = $this->getPageBuffer($this->page);
										$tstart = substr($pagebuff, 0, $this->cntmrk[$this->page]);
										$tend = substr($pagebuff, $this->cntmrk[$this->page]);
										$yshift = $minstartliney - $this->y;
										$try = sprintf('1 0 0 1 0 %.3F cm', $yshift * $this->k);
										$this->setPageBuffer($this->page, $tstart . '' . "\n" . 'q' . "\n" . '' . $try . "\n" . $linebeg . '' . "\n" . 'Q' . "\n" . '' . $tend);

										if (isset($this->PageAnnots[$this->page])) {
											$next_pask = count($this->PageAnnots[$this->page]);
										}
										else {
											$next_pask = 0;
										}

										if (isset($this->PageAnnots[$startlinepage])) {
											foreach ($this->PageAnnots[$startlinepage] as $pak => $pac) {
												if ($pask <= $pak) {
													$this->PageAnnots[$this->page][] = $pac;
													unset($this->PageAnnots[$startlinepage][$pak]);
													$npak = count($this->PageAnnots[$this->page]) - 1;
													$this->PageAnnots[$this->page][$npak]['y'] -= $yshift;
												}
											}
										}

										$pask = $next_pask;
									}

									if (($dom[$key]['value'] != 'td') && ($dom[$key]['value'] != 'th')) {
										$this->y += ($curfontsize - $fontsize) / $this->k;
									}

									$minstartliney = min($this->y, $minstartliney);
								}

								$curfontname = $fontname;
								$curfontstyle = $fontstyle;
								$curfontsize = $fontsize;
							}
						}
					}

					if (($plalign == 'J') && in_array($dom[$key]['value'], $blocktags)) {
						$plalign = '';
					}

					$curpos = $this->pagelen[$startlinepage];
					if (isset($dom[$key]['bgcolor']) && ($dom[$key]['bgcolor'] !== false)) {
						$this->SetFillColorArray($dom[$key]['bgcolor']);
						$wfill = true;
					}
					else {
						$wfill = $fill | false;
					}

					if (isset($dom[$key]['fgcolor']) && ($dom[$key]['fgcolor'] !== false)) {
						$this->SetTextColorArray($dom[$key]['fgcolor']);
					}

					if (isset($dom[$key]['align'])) {
						$lalign = $dom[$key]['align'];
					}

					if ($this->empty_string($lalign)) {
						$lalign = $align;
					}
				}

				if ($this->newline && (0 < strlen($dom[$key]['value'])) && ($dom[$key]['value'] != 'td') && ($dom[$key]['value'] != 'th')) {
					$newline = true;

					if (isset($startlinex)) {
						$yshift = $minstartliney - $startliney;
						if ((0 < $yshift) || ($startlinepage < $this->page)) {
							$yshift = 0;
						}

						if ((isset($plalign) && (($plalign == 'C') || ($plalign == 'J') || (($plalign == 'R') && !$this->rtl) || (($plalign == 'L') && $this->rtl))) || ($yshift < 0)) {
							$linew = abs($this->endlinex - $startlinex);
							$pstart = substr($this->getPageBuffer($startlinepage), 0, $startlinepos);
							if (isset($opentagpos) && isset($this->footerlen[$startlinepage]) && !$this->InFooter) {
								$this->footerpos[$startlinepage] = $this->pagelen[$startlinepage] - $this->footerlen[$startlinepage];
								$midpos = min($opentagpos, $this->footerpos[$startlinepage]);
							}
							else if (isset($opentagpos)) {
								$midpos = $opentagpos;
							}
							else {
								if (isset($this->footerlen[$startlinepage]) && !$this->InFooter) {
									$this->footerpos[$startlinepage] = $this->pagelen[$startlinepage] - $this->footerlen[$startlinepage];
									$midpos = $this->footerpos[$startlinepage];
								}
								else {
									$midpos = 0;
								}
							}

							if (0 < $midpos) {
								$pmid = substr($this->getPageBuffer($startlinepage), $startlinepos, $midpos - $startlinepos);
								$pend = substr($this->getPageBuffer($startlinepage), $midpos);
							}
							else {
								$pmid = substr($this->getPageBuffer($startlinepage), $startlinepos);
								$pend = '';
							}

							$tw = $w;

							if ($this->lMargin != $prevlMargin) {
								$tw += $prevlMargin - $this->lMargin;
							}

							if ($this->rMargin != $prevrMargin) {
								$tw += $prevrMargin - $this->rMargin;
							}

							$mdiff = abs($tw - $linew);
							$t_x = 0;

							if ($plalign == 'C') {
								if ($this->rtl) {
									$t_x = 0 - ($mdiff / 2);
								}
								else {
									$t_x = $mdiff / 2;
								}
							}
							else {
								if (($plalign == 'R') && !$this->rtl) {
									$t_x = $mdiff;
								}
								else {
									if (($plalign == 'L') && $this->rtl) {
										$t_x = 0 - $mdiff;
									}
									else {
										if (($plalign == 'J') && ($plalign == $lalign)) {
											if ($this->rtl || $this->tmprtl) {
												$t_x = $this->lMargin - $this->endlinex;
											}

											$no = 0;
											$ns = 0;
											$pmidtemp = $pmid;
											$pmidtemp = preg_replace('/[\\\\][\\(]/x', '\\#!#OP#!#', $pmidtemp);
											$pmidtemp = preg_replace('/[\\\\][\\)]/x', '\\#!#CP#!#', $pmidtemp);

											if (preg_match_all('/\\[\\(([^\\)]*)\\)\\]/x', $pmidtemp, $lnstring, PREG_PATTERN_ORDER)) {
												$maxkk = count($lnstring[1]) - 1;

												for ($kk = 0; $kk <= $maxkk; ++$kk) {
													$lnstring[1][$kk] = str_replace('#!#OP#!#', '(', $lnstring[1][$kk]);
													$lnstring[1][$kk] = str_replace('#!#CP#!#', ')', $lnstring[1][$kk]);

													if ($kk == $maxkk) {
														if ($this->rtl || $this->tmprtl) {
															$tvalue = ltrim($lnstring[1][$kk]);
														}
														else {
															$tvalue = rtrim($lnstring[1][$kk]);
														}
													}
													else {
														$tvalue = $lnstring[1][$kk];
													}

													$no += substr_count($lnstring[1][$kk], chr(32));
													$ns += substr_count($tvalue, chr(32));
												}

												if ($this->rtl || $this->tmprtl) {
													$t_x = $this->lMargin - $this->endlinex - (($no - $ns - 1) * $this->GetStringWidth(chr(32)));
												}

												$spacelen = $this->GetStringWidth(chr(32));
												$spacewidth = ((($tw - $linew) + (($no - $ns) * $spacelen)) / ($ns ? $ns : 1)) * $this->k;
												$spacewidthu = (-1000 * (($tw - $linew) + ($no * $spacelen))) / ($ns ? $ns : 1) / $this->FontSize;
												$nsmax = $ns;
												$ns = 0;
												reset($lnstring);
												$offset = 0;
												$strcount = 0;
												$prev_epsposbeg = 0;
												global $spacew;

												while (preg_match('/([0-9\\.\\+\\-]*)[\\s](Td|cm|m|l|c|re)[\\s]/x', $pmid, $strpiece, PREG_OFFSET_CAPTURE, $offset) == 1) {
													$stroffset = strpos($pmid, '[(', $offset);
													if (($stroffset !== false) && ($stroffset <= $strpiece[2][1])) {
														$offset = strpos($pmid, ')]', $stroffset);

														while (($offset !== false) && ($pmid[$offset - 1] == '\\')) {
															$offset = strpos($pmid, ')]', $offset + 1);
														}

														if ($offset === false) {
															$this->Error('HTML Justification: malformed PDF code.');
														}

														continue;
													}

													if ($this->rtl || $this->tmprtl) {
														$spacew = $spacewidth * ($nsmax - $ns);
													}
													else {
														$spacew = $spacewidth * $ns;
													}

													$offset = $strpiece[2][1] + strlen($strpiece[2][0]);
													$epsposbeg = strpos($pmid, 'q' . $this->epsmarker, $offset);
													$epsposend = strpos($pmid, $this->epsmarker . 'Q', $offset) + strlen($this->epsmarker . 'Q');
													if (((0 < $epsposbeg) && (0 < $epsposend) && ($epsposbeg < $offset) && ($offset < $epsposend)) || (($epsposbeg === false) && (0 < $epsposend) && ($offset < $epsposend))) {
														$trx = sprintf('1 0 0 1 %.3F 0 cm', $spacew);
														$epsposbeg = strpos($pmid, 'q' . $this->epsmarker, $prev_epsposbeg - 6);
														$pmid_b = substr($pmid, 0, $epsposbeg);
														$pmid_m = substr($pmid, $epsposbeg, $epsposend - $epsposbeg);
														$pmid_e = substr($pmid, $epsposend);
														$pmid = $pmid_b . '' . "\n" . 'q' . "\n" . '' . $trx . "\n" . $pmid_m . '' . "\n" . 'Q' . "\n" . '' . $pmid_e;
														$offset = $epsposend;
														continue;
													}

													$prev_epsposbeg = $epsposbeg;
													$currentxpos = 0;

													switch ($strpiece[2][0]) {
													case 'Td':
													case 'cm':
													case 'm':
													case 'l':
														preg_match('/([0-9\\.\\+\\-]*)[\\s](' . $strpiece[1][0] . ')[\\s](' . $strpiece[2][0] . ')([\\s]*)/x', $pmid, $xmatches);
														$currentxpos = $xmatches[1];
														if (($strcount <= $maxkk) && ($strpiece[2][0] == 'Td')) {
															if ($strcount == $maxkk) {
																if ($this->rtl || $this->tmprtl) {
																	$tvalue = $lnstring[1][$strcount];
																}
																else {
																	$tvalue = rtrim($lnstring[1][$strcount]);
																}
															}
															else {
																$tvalue = $lnstring[1][$strcount];
															}

															$ns += substr_count($tvalue, chr(32));
															++$strcount;
														}

														if ($this->rtl || $this->tmprtl) {
															$spacew = $spacewidth * ($nsmax - $ns);
														}

														$pmid = preg_replace_callback('/([0-9\\.\\+\\-]*)[\\s](' . $strpiece[1][0] . ')[\\s](' . $strpiece[2][0] . ')([\\s]*)/x', create_function('$matches', 'global $spacew;' . "\n" . '													$newx = sprintf("%.2F",(floatval($matches[1]) + $spacew));' . "\n" . '													return "".$newx." ".$matches[2]." x*#!#*x".$matches[3].$matches[4];'), $pmid, 1);
														break;

													case 're':
														preg_match('/([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s](' . $strpiece[1][0] . ')[\\s](' . $strpiece[2][0] . ')([\\s]*)/x', $pmid, $xmatches);
														$currentxpos = $xmatches[1];
														$pmid = preg_replace_callback('/([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s](' . $strpiece[1][0] . ')[\\s](' . $strpiece[2][0] . ')([\\s]*)/x', create_function('$matches', 'global $spacew;' . "\n" . '													$newx = sprintf("%.2F",(floatval($matches[1]) + $spacew));' . "\n" . '													return "".$newx." ".$matches[2]." ".$matches[3]." ".$matches[4]." x*#!#*x".$matches[5].$matches[6];'), $pmid, 1);
														break;

													case 'c':
														preg_match('/([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s](' . $strpiece[1][0] . ')[\\s](' . $strpiece[2][0] . ')([\\s]*)/x', $pmid, $xmatches);
														$currentxpos = $xmatches[1];
														$pmid = preg_replace_callback('/([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s]([0-9\\.\\+\\-]*)[\\s](' . $strpiece[1][0] . ')[\\s](' . $strpiece[2][0] . ')([\\s]*)/x', create_function('$matches', 'global $spacew;' . "\n" . '													$newx1 = sprintf("%.3F",(floatval($matches[1]) + $spacew));' . "\n" . '													$newx2 = sprintf("%.3F",(floatval($matches[3]) + $spacew));' . "\n" . '													$newx3 = sprintf("%.3F",(floatval($matches[5]) + $spacew));' . "\n" . '													return "".$newx1." ".$matches[2]." ".$newx2." ".$matches[4]." ".$newx3." ".$matches[6]." x*#!#*x".$matches[7].$matches[8];'), $pmid, 1);
														break;
													}

													if (isset($this->PageAnnots[$this->page])) {
														foreach ($this->PageAnnots[$this->page] as $pak => $pac) {
															if (($minstartliney <= $pac['y']) && (($currentxpos - $this->feps) <= $pac['x'] * $this->k) && (($pac['x'] * $this->k) <= $currentxpos + $this->feps)) {
																$this->PageAnnots[$this->page][$pak]['x'] += $spacew / $this->k;
																$this->PageAnnots[$this->page][$pak]['w'] += ($spacewidth * $pac['numspaces']) / $this->k;
																break;
															}
														}
													}
												}

												$pmid = str_replace('x*#!#*x', '', $pmid);
												if (($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) {
													$spacew = $spacewidthu;
													$pmidtemp = $pmid;
													$pmidtemp = preg_replace('/[\\\\][\\(]/x', '\\#!#OP#!#', $pmidtemp);
													$pmidtemp = preg_replace('/[\\\\][\\)]/x', '\\#!#CP#!#', $pmidtemp);
													$pmid = preg_replace_callback('/\\[\\(([^\\)]*)\\)\\]/x', create_function('$matches', 'global $spacew;' . "\n" . '													$matches[1] = str_replace("#!#OP#!#", "(", $matches[1]);' . "\n" . '													$matches[1] = str_replace("#!#CP#!#", ")", $matches[1]);' . "\n" . '													return "[(".str_replace(chr(0).chr(32), ") ".($spacew)." (", $matches[1]).")]";'), $pmidtemp);
													$this->setPageBuffer($startlinepage, $pstart . "\n" . $pmid . "\n" . $pend);
													$endlinepos = strlen($pstart . "\n" . $pmid . "\n");
												}
												else {
													$rs = sprintf('%.3F Tw', $spacewidth);
													$pmid = preg_replace('/\\[\\(/x', $rs . ' [(', $pmid);
													$this->setPageBuffer($startlinepage, $pstart . "\n" . $pmid . '' . "\n" . 'BT 0 Tw ET' . "\n" . '' . $pend);
													$endlinepos = strlen($pstart . "\n" . $pmid . '' . "\n" . 'BT 0 Tw ET' . "\n" . '');
												}
											}
										}
									}
								}
							}

							if (($t_x != 0) || ($yshift < 0)) {
								$trx = sprintf('1 0 0 1 %.3F %.3F cm', $t_x * $this->k, $yshift * $this->k);
								$this->setPageBuffer($startlinepage, $pstart . '' . "\n" . 'q' . "\n" . '' . $trx . "\n" . $pmid . '' . "\n" . 'Q' . "\n" . '' . $pend);
								$endlinepos = strlen($pstart . '' . "\n" . 'q' . "\n" . '' . $trx . "\n" . $pmid . '' . "\n" . 'Q' . "\n" . '');

								if (isset($this->PageAnnots[$this->page])) {
									foreach ($this->PageAnnots[$this->page] as $pak => $pac) {
										if ($pask <= $pak) {
											$this->PageAnnots[$this->page][$pak]['x'] += $t_x;
											$this->PageAnnots[$this->page][$pak]['y'] -= $yshift;
										}
									}
								}

								$this->y -= $yshift;
							}
						}
					}

					$this->newline = false;
					$pbrk = $this->checkPageBreak($this->lasth);
					$this->SetFont($fontname, $fontstyle, $fontsize);

					if ($wfill) {
						$this->SetFillColorArray($this->bgcolor);
					}

					$startlinex = $this->x;
					$startliney = $this->y;
					$minstartliney = $this->y;
					$startlinepage = $this->page;
					if (isset($endlinepos) && !$pbrk) {
						$startlinepos = $endlinepos;
						unset($endlinepos);
					}
					else {
						if (isset($this->footerlen[$this->page])) {
							$this->footerpos[$this->page] = $this->pagelen[$this->page] - $this->footerlen[$this->page];
						}
						else {
							$this->footerpos[$this->page] = $this->pagelen[$this->page];
						}

						$startlinepos = $this->footerpos[$this->page];
					}

					$plalign = $lalign;

					if (isset($this->PageAnnots[$this->page])) {
						$pask = count($this->PageAnnots[$this->page]);
					}
					else {
						$pask = 0;
					}
				}

				if (isset($opentagpos)) {
					unset($opentagpos);
				}

				if ($dom[$key]['tag']) {
					if ($dom[$key]['opening']) {
						if (isset($dom[$key]['text-indent']) && in_array($dom[$key]['value'], array('blockquote', 'dd', 'div', 'dt', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'ol', 'p', 'ul', 'table', 'tr', 'td'))) {
							$this->textindent = $dom[$key]['text-indent'];
						}

						if ($dom[$key]['value'] == 'table') {
							if ($this->rtl) {
								$wtmp = $this->x - $this->lMargin;
							}
							else {
								$wtmp = $this->w - $this->rMargin - $this->x;
							}

							$wtmp -= 2 * $this->cMargin;

							if (isset($dom[$key]['width'])) {
								$table_width = $this->getHTMLUnitToUnits($dom[$key]['width'], $wtmp, 'px');
							}
							else {
								$table_width = $wtmp;
							}
						}

						if (($dom[$key]['value'] == 'td') || ($dom[$key]['value'] == 'th')) {
							$trid = $dom[$key]['parent'];
							$table_el = $dom[$trid]['parent'];

							if (!isset($dom[$table_el]['cols'])) {
								$dom[$table_el]['cols'] = $trid['cols'];
							}

							$oldmargin = $this->cMargin;

							if (isset($dom[$dom[$trid]['parent']]['attribute']['cellpadding'])) {
								$currentcmargin = $this->getHTMLUnitToUnits($dom[$dom[$trid]['parent']]['attribute']['cellpadding'], 1, 'px');
							}
							else {
								$currentcmargin = 0;
							}

							$this->cMargin = $currentcmargin;

							if (isset($dom[$dom[$trid]['parent']]['attribute']['cellspacing'])) {
								$cellspacing = $this->getHTMLUnitToUnits($dom[$dom[$trid]['parent']]['attribute']['cellspacing'], 1, 'px');
							}
							else {
								$cellspacing = 0;
							}

							if ($this->rtl) {
								$cellspacingx = 0 - $cellspacing;
							}
							else {
								$cellspacingx = $cellspacing;
							}

							$colspan = $dom[$key]['attribute']['colspan'];
							$wtmp = $colspan * ($table_width / $dom[$table_el]['cols']);

							if (isset($dom[$key]['width'])) {
								$cellw = $this->getHTMLUnitToUnits($dom[$key]['width'], $table_width, 'px');
							}
							else {
								$cellw = $wtmp;
							}

							if (isset($dom[$key]['height'])) {
								$cellh = $this->getHTMLUnitToUnits($dom[$key]['height'], 0, 'px');
							}
							else {
								$cellh = 0;
							}

							$cellw -= $cellspacing;

							if (isset($dom[$key]['content'])) {
								$cell_content = $dom[$key]['content'];
							}
							else {
								$cell_content = '&nbsp;';
							}

							$tagtype = $dom[$key]['value'];
							$parentid = $key;

							while (($key < $maxel) && !($dom[$key]['tag'] && !$dom[$key]['opening'] && ($dom[$key]['value'] == $tagtype) && ($dom[$key]['parent'] == $parentid))) {
								++$key;
							}

							if (!isset($dom[$trid]['startpage'])) {
								$dom[$trid]['startpage'] = $this->page;
							}
							else {
								$this->setPage($dom[$trid]['startpage']);
							}

							if (!isset($dom[$trid]['starty'])) {
								$dom[$trid]['starty'] = $this->y;
							}
							else {
								$this->y = $dom[$trid]['starty'];
							}

							if (!isset($dom[$trid]['startx'])) {
								$dom[$trid]['startx'] = $this->x;
							}

							$this->x += $cellspacingx / 2;

							if (isset($dom[$parentid]['attribute']['rowspan'])) {
								$rowspan = intval($dom[$parentid]['attribute']['rowspan']);
							}
							else {
								$rowspan = 1;
							}

							if (isset($dom[$table_el]['rowspans'])) {
								$rsk = 0;
								$rskmax = count($dom[$table_el]['rowspans']);

								while ($rsk < $rskmax) {
									$trwsp = $dom[$table_el]['rowspans'][$rsk];
									$rsstartx = $trwsp['startx'];
									$rsendx = $trwsp['endx'];

									if ($trwsp['startpage'] < $this->page) {
										if ($this->rtl && ($this->pagedim[$this->page]['orm'] != $this->pagedim[$trwsp['startpage']]['orm'])) {
											$dl = $this->pagedim[$this->page]['orm'] - $this->pagedim[$trwsp['startpage']]['orm'];
											$rsstartx -= $dl;
											$rsendx -= $dl;
										}
										else {
											if (!$this->rtl && ($this->pagedim[$this->page]['olm'] != $this->pagedim[$trwsp['startpage']]['olm'])) {
												$dl = $this->pagedim[$this->page]['olm'] - $this->pagedim[$trwsp['startpage']]['olm'];
												$rsstartx += $dl;
												$rsendx += $dl;
											}
										}
									}

									if ((0 < $trwsp['rowspan']) && (($this->x - $cellspacing - $currentcmargin - $this->feps) < $rsstartx) && ($rsstartx < ($this->x + $cellspacing + $currentcmargin + $this->feps)) && (($trwsp['starty'] < ($this->y - $this->feps)) || ($trwsp['startpage'] < $this->page))) {
										$this->x = $rsendx + $cellspacingx;
										if (($trwsp['rowspan'] == 1) && isset($dom[$trid]['endy']) && isset($dom[$trid]['endpage']) && ($trwsp['endpage'] == $dom[$trid]['endpage'])) {
											$dom[$table_el]['rowspans'][$rsk]['endy'] = max($dom[$trid]['endy'], $trwsp['endy']);
											$dom[$trid]['endy'] = $dom[$table_el]['rowspans'][$rsk]['endy'];
										}

										$rsk = 0;
									}
									else {
										++$rsk;
									}
								}
							}

							if (1 < $rowspan) {
								if (isset($this->footerlen[$this->page])) {
									$this->footerpos[$this->page] = $this->pagelen[$this->page] - $this->footerlen[$this->page];
								}
								else {
									$this->footerpos[$this->page] = $this->pagelen[$this->page];
								}

								$trintmrkpos = $this->footerpos[$this->page];
								$trsid = array_push($dom[$table_el]['rowspans'], array('trid' => $trid, 'rowspan' => $rowspan, 'mrowspan' => $rowspan, 'colspan' => $colspan, 'startpage' => $this->page, 'startx' => $this->x, 'starty' => $this->y, 'intmrkpos' => $trintmrkpos));
							}

							$cellid = array_push($dom[$trid]['cellpos'], array('startx' => $this->x));

							if (1 < $rowspan) {
								$dom[$trid]['cellpos'][$cellid - 1]['rowspanid'] = $trsid - 1;
							}

							if (isset($dom[$parentid]['bgcolor']) && ($dom[$parentid]['bgcolor'] !== false)) {
								$dom[$trid]['cellpos'][$cellid - 1]['bgcolor'] = $dom[$parentid]['bgcolor'];
							}

							$prevLastH = $this->lasth;
							$this->MultiCell($cellw, $cellh, $cell_content, false, $lalign, false, 2, '', '', true, 0, true);
							$this->lasth = $prevLastH;
							$this->cMargin = $oldmargin;
							$dom[$trid]['cellpos'][$cellid - 1]['endx'] = $this->x;

							if ($rowspan <= 1) {
								if (isset($dom[$trid]['endy'])) {
									if ($this->page == $dom[$trid]['endpage']) {
										$dom[$trid]['endy'] = max($this->y, $dom[$trid]['endy']);
									}
									else if ($dom[$trid]['endpage'] < $this->page) {
										$dom[$trid]['endy'] = $this->y;
									}
								}
								else {
									$dom[$trid]['endy'] = $this->y;
								}

								if (isset($dom[$trid]['endpage'])) {
									$dom[$trid]['endpage'] = max($this->page, $dom[$trid]['endpage']);
								}
								else {
									$dom[$trid]['endpage'] = $this->page;
								}
							}
							else {
								$dom[$table_el]['rowspans'][$trsid - 1]['endx'] = $this->x;
								$dom[$table_el]['rowspans'][$trsid - 1]['endy'] = $this->y;
								$dom[$table_el]['rowspans'][$trsid - 1]['endpage'] = $this->page;
							}

							if (isset($dom[$table_el]['rowspans'])) {
								foreach ($dom[$table_el]['rowspans'] as $k => $trwsp) {
									if (0 < $trwsp['rowspan']) {
										if (isset($dom[$trid]['endpage'])) {
											if ($trwsp['endpage'] == $dom[$trid]['endpage']) {
												$dom[$table_el]['rowspans'][$k]['endy'] = max($dom[$trid]['endy'], $trwsp['endy']);
											}
											else if ($trwsp['endpage'] < $dom[$trid]['endpage']) {
												$dom[$table_el]['rowspans'][$k]['endy'] = $dom[$trid]['endy'];
												$dom[$table_el]['rowspans'][$k]['endpage'] = $dom[$trid]['endpage'];
											}
											else {
												$dom[$trid]['endy'] = $this->pagedim[$dom[$trid]['endpage']]['hk'] - $this->pagedim[$dom[$trid]['endpage']]['bm'];
											}
										}
									}
								}
							}

							$this->x += $cellspacingx / 2;
						}
						else {
							if (!isset($opentagpos)) {
								if (!$this->InFooter) {
									if (isset($this->footerlen[$this->page])) {
										$this->footerpos[$this->page] = $this->pagelen[$this->page] - $this->footerlen[$this->page];
									}
									else {
										$this->footerpos[$this->page] = $this->pagelen[$this->page];
									}

									$opentagpos = $this->footerpos[$this->page];
								}
							}

							$this->openHTMLTagHandler($dom, $key, $cell);
						}
					}
					else {
						$this->closeHTMLTagHandler($dom, $key, $cell);
					}
				}
				else if (0 < strlen($dom[$key]['value'])) {
					if (!$this->empty_string($this->lispacer)) {
						$this->SetFont($pfontname, $pfontstyle, $pfontsize);
						$this->lasth = $this->FontSize * $this->cell_height_ratio;
						$minstartliney = $this->y;
						$this->putHtmlListBullet($this->listnum, $this->lispacer, $pfontsize);
						$this->SetFont($curfontname, $curfontstyle, $curfontsize);
						$this->lasth = $this->FontSize * $this->cell_height_ratio;
						if (is_numeric($pfontsize) && (0 < $pfontsize) && is_numeric($curfontsize) && (0 < $curfontsize) && ($pfontsize != $curfontsize)) {
							$this->y += ($pfontsize - $curfontsize) / $this->k;
							$minstartliney = min($this->y, $minstartliney);
						}
					}

					$this->htmlvspace = 0;
					if (!$this->premode && ($this->rtl || $this->tmprtl)) {
						$len1 = strlen($dom[$key]['value']);
						$lsp = $len1 - strlen(ltrim($dom[$key]['value']));
						$rsp = $len1 - strlen(rtrim($dom[$key]['value']));
						$tmpstr = '';

						if (0 < $rsp) {
							$tmpstr .= substr($dom[$key]['value'], 0 - $rsp);
						}

						$tmpstr .= trim($dom[$key]['value']);

						if (0 < $lsp) {
							$tmpstr .= substr($dom[$key]['value'], 0, $lsp);
						}

						$dom[$key]['value'] = $tmpstr;
					}

					if ($newline) {
						if (!$this->premode) {
							if ($this->rtl || $this->tmprtl) {
								$dom[$key]['value'] = rtrim($dom[$key]['value']);
							}
							else {
								$dom[$key]['value'] = ltrim($dom[$key]['value']);
							}
						}

						$newline = false;
						$firstblock = true;
					}
					else {
						$firstblock = false;
					}

					$strrest = '';
					if (!empty($this->HREF) && isset($this->HREF['url'])) {
						$strrest = $this->addHtmlLink($this->HREF['url'], $dom[$key]['value'], $wfill, true, $this->HREF['color'], $this->HREF['style']);
					}
					else {
						$ctmpmargin = $this->cMargin;
						$this->cMargin = 0;

						if ($this->rtl) {
							$this->x -= $this->textindent;
						}
						else {
							$this->x += $this->textindent;
						}

						$strrest = $this->Write($this->lasth, $dom[$key]['value'], '', $wfill, '', false, 0, true, $firstblock);
						$this->textindent = 0;
						$this->cMargin = $ctmpmargin;
					}

					if (0 < strlen($strrest)) {
						$this->newline = true;

						if ($cell) {
							if ($this->rtl) {
								$this->x -= $this->cMargin;
							}
							else {
								$this->x += $this->cMargin;
							}
						}

						if ($strrest == $dom[$key]['value']) {
							++$loop;
						}
						else {
							$loop = 0;
						}

						$dom[$key]['value'] = ltrim($strrest);

						if ($loop < 3) {
							--$key;
						}
					}
					else {
						$loop = 0;
					}
				}

				++$key;
				if (isset($dom[$key]['tag']) && $dom[$key]['tag'] && (!isset($dom[$key]['opening']) || !$dom[$key]['opening']) && isset($dom[$dom[$key]['parent']]['attribute']['nobr']) && ($dom[$dom[$key]['parent']]['attribute']['nobr'] == 'true')) {
					if (!$undo && ($this->start_transaction_page == $this->numpages - 1)) {
						$this->rollbackTransaction(true);

						foreach ($this_method_vars as $vkey => $vval) {
							$$vkey = $vval;
						}

						$this->AddPage();
						$undo = true;
					}
					else {
						$undo = false;
					}
				}
			}

			if (isset($startlinex)) {
				$yshift = $minstartliney - $startliney;
				if ((0 < $yshift) || ($startlinepage < $this->page)) {
					$yshift = 0;
				}

				if ((isset($plalign) && (($plalign == 'C') || ($plalign == 'J') || (($plalign == 'R') && !$this->rtl) || (($plalign == 'L') && $this->rtl))) || ($yshift < 0)) {
					$linew = abs($this->endlinex - $startlinex);
					$pstart = substr($this->getPageBuffer($startlinepage), 0, $startlinepos);
					if (isset($opentagpos) && isset($this->footerlen[$startlinepage]) && !$this->InFooter) {
						$this->footerpos[$startlinepage] = $this->pagelen[$startlinepage] - $this->footerlen[$startlinepage];
						$midpos = min($opentagpos, $this->footerpos[$startlinepage]);
					}
					else if (isset($opentagpos)) {
						$midpos = $opentagpos;
					}
					else {
						if (isset($this->footerlen[$startlinepage]) && !$this->InFooter) {
							$this->footerpos[$startlinepage] = $this->pagelen[$startlinepage] - $this->footerlen[$startlinepage];
							$midpos = $this->footerpos[$startlinepage];
						}
						else {
							$midpos = 0;
						}
					}

					if (0 < $midpos) {
						$pmid = substr($this->getPageBuffer($startlinepage), $startlinepos, $midpos - $startlinepos);
						$pend = substr($this->getPageBuffer($startlinepage), $midpos);
					}
					else {
						$pmid = substr($this->getPageBuffer($startlinepage), $startlinepos);
						$pend = '';
					}

					$tw = $w;

					if ($this->lMargin != $prevlMargin) {
						$tw += $prevlMargin - $this->lMargin;
					}

					if ($this->rMargin != $prevrMargin) {
						$tw += $prevrMargin - $this->rMargin;
					}

					$mdiff = abs($tw - $linew);

					if ($plalign == 'C') {
						if ($this->rtl) {
							$t_x = 0 - ($mdiff / 2);
						}
						else {
							$t_x = $mdiff / 2;
						}
					}
					else {
						if (($plalign == 'R') && !$this->rtl) {
							$t_x = $mdiff;
						}
						else {
							if (($plalign == 'L') && $this->rtl) {
								$t_x = 0 - $mdiff;
							}
							else {
								$t_x = 0;
							}
						}
					}

					if (($t_x != 0) || ($yshift < 0)) {
						$trx = sprintf('1 0 0 1 %.3F %.3F cm', $t_x * $this->k, $yshift * $this->k);
						$this->setPageBuffer($startlinepage, $pstart . '' . "\n" . 'q' . "\n" . '' . $trx . "\n" . $pmid . '' . "\n" . 'Q' . "\n" . '' . $pend);
						$endlinepos = strlen($pstart . '' . "\n" . 'q' . "\n" . '' . $trx . "\n" . $pmid . '' . "\n" . 'Q' . "\n" . '');

						if (isset($this->PageAnnots[$this->page])) {
							foreach ($this->PageAnnots[$this->page] as $pak => $pac) {
								if ($pask <= $pak) {
									$this->PageAnnots[$this->page][$pak]['x'] += $t_x;
									$this->PageAnnots[$this->page][$pak]['y'] -= $yshift;
								}
							}
						}

						$this->y -= $yshift;
					}
				}
			}

			if ($ln && !($cell && ($dom[$key - 1]['value'] == 'table'))) {
				$this->Ln($this->lasth);
			}

			$this->setGraphicVars($gvars);

			if ($prevPage < $this->page) {
				$this->lMargin = $this->pagedim[$this->page]['olm'];
				$this->rMargin = $this->pagedim[$this->page]['orm'];
			}

			$this->listnum = $prev_listnum;
			$this->listordered = $prev_listordered;
			$this->listcount = $prev_listcount;
			$this->lispacer = $prev_lispacer;
			unset($dom);
		}

		protected function openHTMLTagHandler(&$dom, $key, $cell = false)
		{
			$tag = $dom[$key];
			$parent = $dom[$dom[$key]['parent']];
			$firstorlast = $key == 1;

			if (isset($tag['attribute']['dir'])) {
				$this->tmprtl = $tag['attribute']['dir'] == 'rtl' ? 'R' : 'L';
			}
			else {
				$this->tmprtl = false;
			}

			switch ($tag['value']) {
			case 'table':
				$cp = 0;
				$cs = 0;
				$dom[$key]['rowspans'] = array();

				if (!$this->empty_string($dom[$key]['thead'])) {
					$this->thead = $dom[$key]['thead'];
					if (!isset($this->theadMargins) || empty($this->theadMargins)) {
						$this->theadMargins = array();
						$this->theadMargins['cmargin'] = $this->cMargin;
					}
				}

				if (isset($tag['attribute']['cellpadding'])) {
					$cp = $this->getHTMLUnitToUnits($tag['attribute']['cellpadding'], 1, 'px');
					$this->oldcMargin = $this->cMargin;
					$this->cMargin = $cp;
				}

				if (isset($tag['attribute']['cellspacing'])) {
					$cs = $this->getHTMLUnitToUnits($tag['attribute']['cellspacing'], 1, 'px');
				}

				$this->checkPageBreak((2 * $cp) + (2 * $cs) + $this->lasth);
				break;

			case 'tr':
				$dom[$key]['cellpos'] = array();
				break;

			case 'hr':
				$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
				$this->htmlvspace = 0;
				$wtmp = $this->w - $this->lMargin - $this->rMargin;
				if (isset($tag['attribute']['width']) && ($tag['attribute']['width'] != '')) {
					$hrWidth = $this->getHTMLUnitToUnits($tag['attribute']['width'], $wtmp, 'px');
				}
				else {
					$hrWidth = $wtmp;
				}

				$x = $this->GetX();
				$y = $this->GetY();
				$prevlinewidth = $this->GetLineWidth();
				$this->Line($x, $y, $x + $hrWidth, $y);
				$this->SetLineWidth($prevlinewidth);
				$this->addHTMLVertSpace(1, $cell, '', !isset($dom[$key + 1]), $tag['value'], false);
				break;

			case 'a':
				if (array_key_exists('href', $tag['attribute'])) {
					$this->HREF['url'] = $tag['attribute']['href'];
				}

				$this->HREF['color'] = $this->htmlLinkColorArray;
				$this->HREF['style'] = $this->htmlLinkFontStyle;

				if (array_key_exists('style', $tag['attribute'])) {
					preg_match_all('/([^;:\\s]*):([^;]*)/', $tag['attribute']['style'], $style_array, PREG_PATTERN_ORDER);
					$astyle = array();

					while (list($id, $name) = each($style_array[1])) {
						$name = strtolower($name);
						$astyle[$name] = trim($style_array[2][$id]);
					}

					if (isset($astyle['color'])) {
						$this->HREF['color'] = $this->convertHTMLColorToDec($astyle['color']);
					}

					if (isset($astyle['text-decoration'])) {
						$this->HREF['style'] = '';
						$decors = explode(' ', strtolower($astyle['text-decoration']));

						foreach ($decors as $dec) {
							$dec = trim($dec);

							if (!$this->empty_string($dec)) {
								if ($dec[0] == 'u') {
									$this->HREF['style'] .= 'U';
								}
								else if ($dec[0] == 'l') {
									$this->HREF['style'] .= 'D';
								}
							}
						}
					}
				}

				break;

			case 'img':
				if (isset($tag['attribute']['src'])) {
					if (($tag['attribute']['src'][0] == '/') && ($_SERVER['DOCUMENT_ROOT'] != '/')) {
						$tag['attribute']['src'] = $_SERVER['DOCUMENT_ROOT'] . $tag['attribute']['src'];
					}

					$tag['attribute']['src'] = urldecode($tag['attribute']['src']);
					$tag['attribute']['src'] = str_replace(K_PATH_URL, K_PATH_MAIN, $tag['attribute']['src']);

					if (!isset($tag['attribute']['width'])) {
						$tag['attribute']['width'] = 0;
					}

					if (!isset($tag['attribute']['height'])) {
						$tag['attribute']['height'] = 0;
					}

					$tag['attribute']['align'] = 'bottom';

					switch ($tag['attribute']['align']) {
					case 'top':
						$align = 'T';
						break;

					case 'middle':
						$align = 'M';
						break;

					case 'bottom':
						$align = 'B';
						break;

					default:
						$align = 'B';
						break;
					}

					$fileinfo = pathinfo($tag['attribute']['src']);
					if (isset($fileinfo['extension']) && !$this->empty_string($fileinfo['extension'])) {
						$type = strtolower($fileinfo['extension']);
					}

					$prevy = $this->y;
					$xpos = $this->GetX();
					if (isset($dom[$key - 1]) && ($dom[$key - 1]['value'] == ' ')) {
						if ($this->rtl) {
							$xpos += $this->GetStringWidth(' ');
						}
						else {
							$xpos -= $this->GetStringWidth(' ');
						}
					}

					$imglink = '';
					if (isset($this->HREF['url']) && !$this->empty_string($this->HREF['url'])) {
						$imglink = $this->HREF['url'];

						if ($imglink[0] == '#') {
							$page = intval(substr($imglink, 1));
							$imglink = $this->AddLink();
							$this->SetLink($imglink, 0, $page);
						}
					}

					$border = 0;
					if (isset($tag['attribute']['border']) && !empty($tag['attribute']['border'])) {
						$border = $tag['attribute']['border'];
					}

					$iw = '';

					if (isset($tag['attribute']['width'])) {
						$iw = $this->getHTMLUnitToUnits($tag['attribute']['width'], 1, 'px', false);
					}

					$ih = '';

					if (isset($tag['attribute']['height'])) {
						$ih = $this->getHTMLUnitToUnits($tag['attribute']['height'], 1, 'px', false);
					}

					if (($type == 'eps') || ($type == 'ai')) {
						$this->ImageEps($tag['attribute']['src'], $xpos, $this->GetY(), $iw, $ih, $imglink, true, $align, '', $border);
					}
					else {
						$this->Image($tag['attribute']['src'], $xpos, $this->GetY(), $iw, $ih, '', $imglink, $align, false, 300, '', false, false, $border);
					}

					switch ($align) {
					case 'T':
						$this->y = $prevy;
						break;

					case 'M':
						$this->y = (($this->img_rb_y + $prevy) - ($tag['fontsize'] / $this->k)) / 2;
						break;

					case 'B':
						$this->y = $this->img_rb_y - ($tag['fontsize'] / $this->k);
						break;
					}
				}

				break;

			case 'dl':
				++$this->listnum;
				$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], false);
				break;

			case 'dt':
				$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
				break;

			case 'dd':
				if ($this->rtl) {
					$this->rMargin += $this->listindent;
				}
				else {
					$this->lMargin += $this->listindent;
				}

				$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
				break;

			case 'ul':
			case 'ol':
				$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], false);
				$this->htmlvspace = 0;
				++$this->listnum;

				if ($tag['value'] == 'ol') {
					$this->listordered[$this->listnum] = true;
				}
				else {
					$this->listordered[$this->listnum] = false;
				}

				if (isset($tag['attribute']['start'])) {
					$this->listcount[$this->listnum] = intval($tag['attribute']['start']) - 1;
				}
				else {
					$this->listcount[$this->listnum] = 0;
				}

				if ($this->rtl) {
					$this->rMargin += $this->listindent;
				}
				else {
					$this->lMargin += $this->listindent;
				}

				$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], false);
				$this->htmlvspace = 0;
				break;

			case 'li':
				$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);

				if ($this->listordered[$this->listnum]) {
					if (isset($parent['attribute']['type']) && !$this->empty_string($parent['attribute']['type'])) {
						$this->lispacer = $parent['attribute']['type'];
					}
					else {
						if (isset($parent['listtype']) && !$this->empty_string($parent['listtype'])) {
							$this->lispacer = $parent['listtype'];
						}
						else {
							if (isset($this->lisymbol) && !$this->empty_string($this->lisymbol)) {
								$this->lispacer = $this->lisymbol;
							}
							else {
								$this->lispacer = '#';
							}
						}
					}

					++$this->listcount[$this->listnum];

					if (isset($tag['attribute']['value'])) {
						$this->listcount[$this->listnum] = intval($tag['attribute']['value']);
					}
				}
				else {
					if (isset($parent['attribute']['type']) && !$this->empty_string($parent['attribute']['type'])) {
						$this->lispacer = $parent['attribute']['type'];
					}
					else {
						if (isset($parent['listtype']) && !$this->empty_string($parent['listtype'])) {
							$this->lispacer = $parent['listtype'];
						}
						else {
							if (isset($this->lisymbol) && !$this->empty_string($this->lisymbol)) {
								$this->lispacer = $this->lisymbol;
							}
							else {
								$this->lispacer = '!';
							}
						}
					}
				}

				break;

			case 'blockquote':
				if ($this->rtl) {
					$this->rMargin += $this->listindent;
				}
				else {
					$this->lMargin += $this->listindent;
				}

				$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], false);
				break;

			case 'br':
				$this->Ln('', $cell);
				break;

			case 'div':
				$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
				break;

			case 'p':
				$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], false);
				break;

			case 'pre':
				$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
				$this->premode = true;
				break;

			case 'sup':
				$this->SetXY($this->GetX(), $this->GetY() - ((0.7 * $this->FontSizePt) / $this->k));
				break;

			case 'sub':
				$this->SetXY($this->GetX(), $this->GetY() + ((0.3 * $this->FontSizePt) / $this->k));
				break;

			case 'h1':
			case 'h2':
			case 'h3':
			case 'h4':
			case 'h5':
			case 'h6':
				$this->addHTMLVertSpace(1, $cell, ($tag['fontsize'] * 1.5) / $this->k, $firstorlast, $tag['value'], false);
				break;

			case 'form':
				if (isset($tag['attribute']['action'])) {
					$this->form_action = $tag['attribute']['action'];
				}
				else {
					$this->form_action = K_PATH_URL . $_SERVER['SCRIPT_NAME'];
				}

				if (isset($tag['attribute']['enctype'])) {
					$this->form_enctype = $tag['attribute']['enctype'];
				}
				else {
					$this->form_enctype = 'application/x-www-form-urlencoded';
				}

				if (isset($tag['attribute']['method'])) {
					$this->form_mode = $tag['attribute']['method'];
				}
				else {
					$this->form_mode = 'post';
				}

				break;

			case 'input':
				if (isset($tag['attribute']['name']) && !$this->empty_string($tag['attribute']['name'])) {
					$name = $tag['attribute']['name'];
				}
				else {
					break;
				}

				$prop = array();
				$opt = array();
				if (isset($tag['attribute']['value']) && !$this->empty_string($tag['attribute']['value'])) {
					$value = $tag['attribute']['value'];
				}

				if (isset($tag['attribute']['maxlength']) && !$this->empty_string($tag['attribute']['maxlength'])) {
					$opt['maxlen'] = intval($tag['attribute']['value']);
				}

				$h = $this->FontSize * $this->cell_height_ratio;
				if (isset($tag['attribute']['size']) && !$this->empty_string($tag['attribute']['size'])) {
					$w = intval($tag['attribute']['size']) * $this->GetStringWidth(chr(32)) * 2;
				}
				else {
					$w = $h;
				}

				if (isset($tag['attribute']['checked']) && (($tag['attribute']['checked'] == 'checked') || ($tag['attribute']['checked'] == 'true'))) {
					$checked = true;
				}
				else {
					$checked = false;
				}

				switch ($tag['attribute']['type']) {
				case 'text':
					if (isset($value)) {
						$opt['v'] = $value;
					}

					$this->TextField($name, $w, $h, $prop, $opt, '', '', false);
					break;

				case 'password':
					if (isset($value)) {
						$opt['v'] = $value;
					}

					$prop['password'] = 'true';
					$this->TextField($name, $w, $h, $prop, $opt, '', '', false);
					break;

				case 'checkbox':
					$this->CheckBox($name, $w, $checked, $prop, $opt, $value, '', '', false);
					break;

				case 'radio':
					$this->RadioButton($name, $w, $prop, $opt, $value, $checked, '', '', false);
					break;

				case 'submit':
					$w = $this->GetStringWidth($value) * 1.5;
					$h *= 1.6;
					$prop = array(
						'lineWidth'   => 1,
						'borderStyle' => 'beveled',
						'fillColor'   => array(196, 196, 196),
						'strokeColor' => array(255, 255, 255)
						);
					$action = array();
					$action['S'] = 'SubmitForm';
					$action['F'] = $this->form_action;

					if ($this->form_enctype != 'FDF') {
						$action['Flags'] = array('ExportFormat');
					}

					if ($this->form_mode == 'get') {
						$action['Flags'] = array('GetMethod');
					}

					$this->Button($name, $w, $h, $value, $action, $prop, $opt, '', '', false);
					break;

				case 'reset':
					$w = $this->GetStringWidth($value) * 1.5;
					$h *= 1.6;
					$prop = array(
						'lineWidth'   => 1,
						'borderStyle' => 'beveled',
						'fillColor'   => array(196, 196, 196),
						'strokeColor' => array(255, 255, 255)
						);
					$this->Button($name, $w, $h, $value, array('S' => 'ResetForm'), $prop, $opt, '', '', false);
					break;

				case 'file':
					$prop['fileSelect'] = 'true';
					$this->TextField($name, $w, $h, $prop, $opt, '', '', false);

					if (!isset($value)) {
						$value = '*';
					}

					$w = $this->GetStringWidth($value) * 2;
					$h *= 1.2;
					$prop = array(
						'lineWidth'   => 1,
						'borderStyle' => 'beveled',
						'fillColor'   => array(196, 196, 196),
						'strokeColor' => array(255, 255, 255)
						);
					$jsaction = 'var f=this.getField(\'' . $name . '\'); f.browseForFileToSubmit();';
					$this->Button('FB_' . $name, $w, $h, $value, $jsaction, $prop, $opt, '', '', false);
					break;

				case 'hidden':
					if (isset($value)) {
						$opt['v'] = $value;
					}

					$opt['f'] = array('invisible', 'hidden');
					$this->TextField($name, 0, 0, $prop, $opt, '', '', false);
					break;

				case 'image':
					if (isset($tag['attribute']['src']) && !$this->empty_string($tag['attribute']['src'])) {
						$img = $tag['attribute']['src'];
					}
					else {
						break;
					}

					$value = 'img';
					if (isset($tag['attribute']['onclick']) && !empty($tag['attribute']['onclick'])) {
						$jsaction = $tag['attribute']['onclick'];
					}
					else {
						$jsaction = '';
					}

					$this->Button($name, $w, $h, $value, $jsaction, $prop, $opt, '', '', false);
					break;

				case 'button':
					$w = $this->GetStringWidth($value) * 1.5;
					$h *= 1.6;
					$prop = array(
						'lineWidth'   => 1,
						'borderStyle' => 'beveled',
						'fillColor'   => array(196, 196, 196),
						'strokeColor' => array(255, 255, 255)
						);
					if (isset($tag['attribute']['onclick']) && !empty($tag['attribute']['onclick'])) {
						$jsaction = $tag['attribute']['onclick'];
					}
					else {
						$jsaction = '';
					}

					$this->Button($name, $w, $h, $value, $jsaction, $prop, $opt, '', '', false);
					break;
				}

				break;

			case 'textarea':
				$prop = array();
				$opt = array();
				if (isset($tag['attribute']['name']) && !$this->empty_string($tag['attribute']['name'])) {
					$name = $tag['attribute']['name'];
				}
				else {
					break;
				}

				if (isset($tag['attribute']['value']) && !$this->empty_string($tag['attribute']['value'])) {
					$opt['v'] = $tag['attribute']['value'];
				}

				if (isset($tag['attribute']['cols']) && !$this->empty_string($tag['attribute']['cols'])) {
					$w = intval($tag['attribute']['cols']) * $this->GetStringWidth(chr(32)) * 2;
				}
				else {
					$w = 40;
				}

				if (isset($tag['attribute']['rows']) && !$this->empty_string($tag['attribute']['rows'])) {
					$h = intval($tag['attribute']['rows']) * $this->FontSize * $this->cell_height_ratio;
				}
				else {
					$h = 10;
				}

				$prop['multiline'] = 'true';
				$this->TextField($name, $w, $h, $prop, $opt, '', '', false);
				break;

			case 'select':
				$h = $this->FontSize * $this->cell_height_ratio;
				if (isset($tag['attribute']['size']) && !$this->empty_string($tag['attribute']['size'])) {
					$h *= $tag['attribute']['size'] + 1;
				}

				$prop = array();
				$opt = array();
				if (isset($tag['attribute']['name']) && !$this->empty_string($tag['attribute']['name'])) {
					$name = $tag['attribute']['name'];
				}
				else {
					break;
				}

				$w = 0;
				if (isset($tag['attribute']['opt']) && !$this->empty_string($tag['attribute']['opt'])) {
					$options = explode("\r", $tag['attribute']['opt']);
					$values = array();

					foreach ($options as $val) {
						if (strpos($val, '	') !== false) {
							$opts = explode('	', $val);
							$values[] = $opts;
							$w = max($w, $this->GetStringWidth($opts[1]));
						}
						else {
							$values[] = $val;
							$w = max($w, $this->GetStringWidth($val));
						}
					}
				}
				else {
					break;
				}

				$w *= 2;
				if (isset($tag['attribute']['multiple']) && $tag['attribute']['multiple'] = 'multiple') {
					$prop['multipleSelection'] = 'true';
					$this->ListBox($name, $w, $h, $values, $prop, $opt, '', '', false);
				}
				else {
					$this->ComboBox($name, $w, $h, $values, $prop, $opt, '', '', false);
				}

				break;

			case 'tcpdf':
				if (isset($tag['attribute']['method'])) {
					$tcpdf_method = $tag['attribute']['method'];

					if (method_exists($this, $tcpdf_method)) {
						if (isset($tag['attribute']['params']) && !empty($tag['attribute']['params'])) {
							eval ('$params = array(' . $this->unhtmlentities($tag['attribute']['params']) . ');');
							call_user_func_array(array($this, $tcpdf_method), $params);
						}
						else {
							$this->$tcpdf_method();
						}

						$this->newline = true;
					}
				}
			default:
				break;
			}
		}

		protected function closeHTMLTagHandler(&$dom, $key, $cell = false)
		{
			$tag = $dom[$key];
			$parent = $dom[$dom[$key]['parent']];
			$firstorlast = !isset($dom[$key + 1]) || (!isset($dom[$key + 2]) && ($dom[$key + 1]['value'] == 'marker'));
			$in_table_head = false;

			switch ($tag['value']) {
			case 'tr':
				$table_el = $dom[$dom[$key]['parent']]['parent'];

				if (!isset($parent['endy'])) {
					$dom[$dom[$key]['parent']]['endy'] = $this->y;
					$parent['endy'] = $this->y;
				}

				if (!isset($parent['endpage'])) {
					$dom[$dom[$key]['parent']]['endpage'] = $this->page;
					$parent['endpage'] = $this->page;
				}

				if (isset($dom[$table_el]['rowspans'])) {
					foreach ($dom[$table_el]['rowspans'] as $k => $trwsp) {
						$dom[$table_el]['rowspans'][$k]['rowspan'] -= 1;

						if ($dom[$table_el]['rowspans'][$k]['rowspan'] == 0) {
							if ($dom[$table_el]['rowspans'][$k]['endpage'] == $parent['endpage']) {
								$dom[$dom[$key]['parent']]['endy'] = max($dom[$table_el]['rowspans'][$k]['endy'], $parent['endy']);
							}
							else if ($parent['endpage'] < $dom[$table_el]['rowspans'][$k]['endpage']) {
								$dom[$dom[$key]['parent']]['endy'] = $dom[$table_el]['rowspans'][$k]['endy'];
								$dom[$dom[$key]['parent']]['endpage'] = $dom[$table_el]['rowspans'][$k]['endpage'];
							}
						}
					}

					foreach ($dom[$table_el]['rowspans'] as $k => $trwsp) {
						if ($dom[$table_el]['rowspans'][$k]['rowspan'] == 0) {
							$dom[$table_el]['rowspans'][$k]['endpage'] = max($dom[$table_el]['rowspans'][$k]['endpage'], $dom[$dom[$key]['parent']]['endpage']);
							$dom[$dom[$key]['parent']]['endpage'] = $dom[$table_el]['rowspans'][$k]['endpage'];
							$dom[$table_el]['rowspans'][$k]['endy'] = max($dom[$table_el]['rowspans'][$k]['endy'], $dom[$dom[$key]['parent']]['endy']);
							$dom[$dom[$key]['parent']]['endy'] = $dom[$table_el]['rowspans'][$k]['endy'];
						}
					}

					foreach ($dom[$table_el]['rowspans'] as $k => $trwsp) {
						if ($dom[$table_el]['rowspans'][$k]['rowspan'] == 0) {
							$dom[$table_el]['rowspans'][$k]['endpage'] = $dom[$dom[$key]['parent']]['endpage'];
							$dom[$table_el]['rowspans'][$k]['endy'] = $dom[$dom[$key]['parent']]['endy'];
						}
					}
				}

				$this->setPage($dom[$dom[$key]['parent']]['endpage']);
				$this->y = $dom[$dom[$key]['parent']]['endy'];

				if (isset($dom[$table_el]['attribute']['cellspacing'])) {
					$cellspacing = $this->getHTMLUnitToUnits($dom[$table_el]['attribute']['cellspacing'], 1, 'px');
					$this->y += $cellspacing;
				}

				$this->Ln(0, $cell);
				$this->x = $parent['startx'];

				if ($parent['startpage'] < $this->page) {
					if ($this->rtl && ($this->pagedim[$this->page]['orm'] != $this->pagedim[$parent['startpage']]['orm'])) {
						$this->x += $this->pagedim[$this->page]['orm'] - $this->pagedim[$parent['startpage']]['orm'];
					}
					else {
						if (!$this->rtl && ($this->pagedim[$this->page]['olm'] != $this->pagedim[$parent['startpage']]['olm'])) {
							$this->x += $this->pagedim[$this->page]['olm'] - $this->pagedim[$parent['startpage']]['olm'];
						}
					}
				}

				break;

			case 'tablehead':
				$in_table_head = true;
			case 'table':
				$table_el = $parent;
				if ((isset($table_el['attribute']['border']) && (0 < $table_el['attribute']['border'])) || (isset($table_el['style']['border']) && (0 < $table_el['style']['border']))) {
					$border = 1;
				}
				else {
					$border = 0;
				}

				foreach ($dom[$dom[$key]['parent']]['trids'] as $j => $trkey) {
					if (isset($dom[$dom[$key]['parent']]['rowspans'])) {
						foreach ($dom[$dom[$key]['parent']]['rowspans'] as $k => $trwsp) {
							if ($trwsp['trid'] == $trkey) {
								$dom[$dom[$key]['parent']]['rowspans'][$k]['mrowspan'] -= 1;
							}

							if (isset($prevtrkey) && ($trwsp['trid'] == $prevtrkey) && (0 <= $trwsp['mrowspan'])) {
								$dom[$dom[$key]['parent']]['rowspans'][$k]['trid'] = $trkey;
							}
						}
					}

					if (isset($prevtrkey) && ($dom[$prevtrkey]['endpage'] < $dom[$trkey]['startpage'])) {
						$pgendy = $this->pagedim[$dom[$prevtrkey]['endpage']]['hk'] - $this->pagedim[$dom[$prevtrkey]['endpage']]['bm'];
						$dom[$prevtrkey]['endy'] = $pgendy;

						if (isset($dom[$dom[$key]['parent']]['rowspans'])) {
							foreach ($dom[$dom[$key]['parent']]['rowspans'] as $k => $trwsp) {
								if (($trwsp['trid'] == $trkey) && ($trwsp['mrowspan'] == 1) && ($trwsp['endpage'] == $dom[$prevtrkey]['endpage'])) {
									$dom[$dom[$key]['parent']]['rowspans'][$k]['endy'] = $pgendy;
									$dom[$dom[$key]['parent']]['rowspans'][$k]['mrowspan'] = -1;
								}
							}
						}
					}

					$prevtrkey = $trkey;
					$table_el = $dom[$dom[$key]['parent']];
				}

				foreach ($table_el['trids'] as $j => $trkey) {
					$parent = $dom[$trkey];

					foreach ($parent['cellpos'] as $k => $cellpos) {
						if (isset($cellpos['rowspanid']) && (0 <= $cellpos['rowspanid'])) {
							$cellpos['startx'] = $table_el['rowspans'][$cellpos['rowspanid']]['startx'];
							$cellpos['endx'] = $table_el['rowspans'][$cellpos['rowspanid']]['endx'];
							$endy = $table_el['rowspans'][$cellpos['rowspanid']]['endy'];
							$startpage = $table_el['rowspans'][$cellpos['rowspanid']]['startpage'];
							$endpage = $table_el['rowspans'][$cellpos['rowspanid']]['endpage'];
						}
						else {
							$endy = $parent['endy'];
							$startpage = $parent['startpage'];
							$endpage = $parent['endpage'];
						}

						if ($startpage < $endpage) {
							for ($page = $startpage; $page <= $endpage; ++$page) {
								$this->setPage($page);

								if ($page == $startpage) {
									$this->y = $parent['starty'];
									$ch = $this->getPageHeight() - $parent['starty'] - $this->getBreakMargin();
									$cborder = $this->getBorderMode($border, $position = 'start');
								}
								else if ($page == $endpage) {
									$this->y = $this->tMargin;
									$ch = $endy - $this->tMargin;
									$cborder = $this->getBorderMode($border, $position = 'end');
								}
								else {
									$this->y = $this->tMargin;
									$ch = $this->getPageHeight() - $this->tMargin - $this->getBreakMargin();
									$cborder = $this->getBorderMode($border, $position = 'middle');
								}

								if (isset($cellpos['bgcolor']) && ($cellpos['bgcolor'] !== false)) {
									$this->SetFillColorArray($cellpos['bgcolor']);
									$fill = true;
								}
								else {
									$fill = false;
								}

								$cw = abs($cellpos['endx'] - $cellpos['startx']);
								$this->x = $cellpos['startx'];

								if ($startpage < $page) {
									if ($this->rtl && ($this->pagedim[$page]['orm'] != $this->pagedim[$startpage]['orm'])) {
										$this->x -= $this->pagedim[$page]['orm'] - $this->pagedim[$startpage]['orm'];
									}
									else {
										if (!$this->rtl && ($this->pagedim[$page]['lm'] != $this->pagedim[$startpage]['olm'])) {
											$this->x += $this->pagedim[$page]['olm'] - $this->pagedim[$startpage]['olm'];
										}
									}
								}

								$ccode = $this->FillColor . "\n" . $this->getCellCode($cw, $ch, '', $cborder, 1, '', $fill, '', 0, true);
								if ($cborder || $fill) {
									$pagebuff = $this->getPageBuffer($this->page);
									$pstart = substr($pagebuff, 0, $this->intmrk[$this->page]);
									$pend = substr($pagebuff, $this->intmrk[$this->page]);
									$this->setPageBuffer($this->page, $pstart . $ccode . "\n" . $pend);
									$this->intmrk[$this->page] += strlen($ccode . "\n");
								}
							}
						}
						else {
							$this->setPage($startpage);
							if (isset($cellpos['bgcolor']) && ($cellpos['bgcolor'] !== false)) {
								$this->SetFillColorArray($cellpos['bgcolor']);
								$fill = true;
							}
							else {
								$fill = false;
							}

							$this->x = $cellpos['startx'];
							$this->y = $parent['starty'];
							$cw = abs($cellpos['endx'] - $cellpos['startx']);
							$ch = $endy - $parent['starty'];
							$ccode = $this->FillColor . "\n" . $this->getCellCode($cw, $ch, '', $border, 1, '', $fill, '', 0, true);
							if ($border || $fill) {
								if (end($this->transfmrk[$this->page]) !== false) {
									$pagemarkkey = key($this->transfmrk[$this->page]);
									$pagemark = &$this->transfmrk[$this->page][$pagemarkkey];
								}
								else if ($this->InFooter) {
									$pagemark = &$this->footerpos[$this->page];
								}
								else {
									$pagemark = &$this->intmrk[$this->page];
								}

								$pagebuff = $this->getPageBuffer($this->page);
								$pstart = substr($pagebuff, 0, $pagemark);
								$pend = substr($pagebuff, $pagemark);
								$this->setPageBuffer($this->page, $pstart . $ccode . "\n" . $pend);
								$pagemark += strlen($ccode . "\n");
							}
						}
					}

					if (isset($table_el['attribute']['cellspacing'])) {
						$cellspacing = $this->getHTMLUnitToUnits($table_el['attribute']['cellspacing'], 1, 'px');
						$this->y += $cellspacing;
					}

					$this->Ln(0, $cell);
					$this->x = $parent['startx'];

					if ($startpage < $endpage) {
						if ($this->rtl && ($this->pagedim[$endpage]['orm'] != $this->pagedim[$startpage]['orm'])) {
							$this->x += $this->pagedim[$endpage]['orm'] - $this->pagedim[$startpage]['orm'];
						}
						else {
							if (!$this->rtl && ($this->pagedim[$endpage]['olm'] != $this->pagedim[$startpage]['olm'])) {
								$this->x += $this->pagedim[$endpage]['olm'] - $this->pagedim[$startpage]['olm'];
							}
						}
					}
				}

				if (!$in_table_head) {
					if (isset($parent['cellpadding'])) {
						$this->cMargin = $this->oldcMargin;
					}

					$this->lasth = $this->FontSize * $this->cell_height_ratio;

					if (isset($this->theadMargins['top'])) {
						$this->tMargin = $this->theadMargins['top'];
						$this->pagedim[$this->page]['tm'] = $this->tMargin;
					}

					$this->thead = '';
					$this->theadMargins = array();
				}

				break;

			case 'a':
				$this->HREF = '';
				break;

			case 'sup':
				$this->SetXY($this->GetX(), $this->GetY() + ((0.7 * $parent['fontsize']) / $this->k));
				break;

			case 'sub':
				$this->SetXY($this->GetX(), $this->GetY() - ((0.3 * $parent['fontsize']) / $this->k));
				break;

			case 'div':
				$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], true);
				break;

			case 'blockquote':
				if ($this->rtl) {
					$this->rMargin -= $this->listindent;
				}
				else {
					$this->lMargin -= $this->listindent;
				}

				$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], true);
				break;

			case 'p':
				$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], true);
				break;

			case 'pre':
				$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], true);
				$this->premode = false;
				break;

			case 'dl':
				--$this->listnum;

				if ($this->listnum <= 0) {
					$this->listnum = 0;
					$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], true);
				}

				break;

			case 'dt':
				$this->lispacer = '';
				$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], true);
				break;

			case 'dd':
				$this->lispacer = '';

				if ($this->rtl) {
					$this->rMargin -= $this->listindent;
				}
				else {
					$this->lMargin -= $this->listindent;
				}

				$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], true);
				break;

			case 'ul':
			case 'ol':
				--$this->listnum;
				$this->lispacer = '';

				if ($this->rtl) {
					$this->rMargin -= $this->listindent;
				}
				else {
					$this->lMargin -= $this->listindent;
				}

				if ($this->listnum <= 0) {
					$this->listnum = 0;
					$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], true);
				}

				$this->lasth = $this->FontSize * $this->cell_height_ratio;
				break;

			case 'li':
				$this->lispacer = '';
				$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], true);
				break;

			case 'h1':
			case 'h2':
			case 'h3':
			case 'h4':
			case 'h5':
			case 'h6':
				$this->addHTMLVertSpace(1, $cell, ($parent['fontsize'] * 1.5) / $this->k, $firstorlast, $tag['value'], true);
				break;

			case 'form':
				$this->form_action = '';
				$this->form_enctype = 'application/x-www-form-urlencoded';
				break;

			default:
				break;
			}

			$this->tmprtl = false;
		}

		protected function addHTMLVertSpace($n, $cell = false, $h = '', $firstorlast = false, $tag = '', $closing = false)
		{
			if ($firstorlast) {
				$this->Ln(0, $cell);
				$this->htmlvspace = 0;
				return NULL;
			}

			if (isset($this->tagvspaces[$tag][intval($closing)]['n'])) {
				$n = $this->tagvspaces[$tag][intval($closing)]['n'];
			}

			if (isset($this->tagvspaces[$tag][intval($closing)]['h'])) {
				$h = $this->tagvspaces[$tag][intval($closing)]['h'];
			}

			if (is_string($h)) {
				$vsize = $n * $this->lasth;
			}
			else {
				$vsize = $n * $h;
			}

			if ($this->htmlvspace < $vsize) {
				$this->Ln($vsize - $this->htmlvspace, $cell);
				$this->htmlvspace = $vsize;
			}
		}

		public function setLIsymbol($symbol = '!')
		{
			$symbol = strtolower($symbol);

			switch ($symbol) {
			case '!':
			case '#':
			case 'disc':
			case 'disc':
			case 'circle':
			case 'square':
			case '1':
			case 'decimal':
			case 'decimal-leading-zero':
			case 'i':
			case 'lower-roman':
			case 'I':
			case 'upper-roman':
			case 'a':
			case 'lower-alpha':
			case 'lower-latin':
			case 'A':
			case 'upper-alpha':
			case 'upper-latin':
			case 'lower-greek':
				$this->lisymbol = $symbol;
				break;

			default:
				$this->lisymbol = '';
			}
		}

		public function SetBooklet($booklet = true, $inner = -1, $outer = -1)
		{
			$this->booklet = $booklet;

			if (0 <= $inner) {
				$this->lMargin = $inner;
			}

			if (0 <= $outer) {
				$this->rMargin = $outer;
			}
		}

		protected function swapMargins($reverse = true)
		{
			if ($reverse) {
				$mtemp = $this->original_lMargin;
				$this->original_lMargin = $this->original_rMargin;
				$this->original_rMargin = $mtemp;
				$deltam = $this->original_lMargin - $this->original_rMargin;
				$this->lMargin += $deltam;
				$this->rMargin -= $deltam;
			}
		}

		public function setHtmlVSpace($tagvs)
		{
			$this->tagvspaces = $tagvs;
		}

		public function setListIndentWidth($width)
		{
			return $this->customlistindent = floatval($width);
		}

		public function setOpenCell($isopen)
		{
			$this->opencell = $isopen;
		}

		public function setHtmlLinksStyle($color = array(0, 0, 255), $fontstyle = 'U')
		{
			$this->htmlLinkColorArray = $color;
			$this->htmlLinkFontStyle = $fontstyle;
		}

		public function getHTMLUnitToUnits($htmlval, $refsize = 1, $defaultunit = 'px', $points = false)
		{
			$supportedunits = array('%', 'em', 'ex', 'px', 'in', 'cm', 'mm', 'pc', 'pt');
			$retval = 0;
			$value = 0;
			$unit = 'px';
			$k = $this->k;

			if ($points) {
				$k = 1;
			}

			if (in_array($defaultunit, $supportedunits)) {
				$unit = $defaultunit;
			}

			if (is_numeric($htmlval)) {
				$value = floatval($htmlval);
			}
			else if (preg_match('/([0-9\\.]+)/', $htmlval, $mnum)) {
				$value = floatval($mnum[1]);

				if (preg_match('/([a-z%]+)/', $htmlval, $munit)) {
					if (in_array($munit[1], $supportedunits)) {
						$unit = $munit[1];
					}
				}
			}

			switch ($unit) {
			case '%':
				$retval = ($value * $refsize) / 100;
				break;

			case 'em':
				$retval = $value * $refsize;
				break;

			case 'ex':
				$retval = $value * ($refsize / 2);
				break;

			case 'in':
				$retval = ($value * $this->dpi) / $k;
				break;

			case 'cm':
				$retval = (($value / 2.54) * $this->dpi) / $k;
				break;

			case 'mm':
				$retval = (($value / 25.4) * $this->dpi) / $k;
				break;

			case 'pc':
				$retval = ($value * 12) / $k;
				break;

			case 'pt':
				$retval = $value / $k;
				break;

			case 'px':
				$retval = $this->pixelsToUnits($value);
				break;
			}

			return $retval;
		}

		public function intToRoman($number)
		{
			$roman = '';

			while (1000 <= $number) {
				$roman .= 'M';
				$number -= 1000;
			}

			while (900 <= $number) {
				$roman .= 'CM';
				$number -= 900;
			}

			while (500 <= $number) {
				$roman .= 'D';
				$number -= 500;
			}

			while (400 <= $number) {
				$roman .= 'CD';
				$number -= 400;
			}

			while (100 <= $number) {
				$roman .= 'C';
				$number -= 100;
			}

			while (90 <= $number) {
				$roman .= 'XC';
				$number -= 90;
			}

			while (50 <= $number) {
				$roman .= 'L';
				$number -= 50;
			}

			while (40 <= $number) {
				$roman .= 'XL';
				$number -= 40;
			}

			while (10 <= $number) {
				$roman .= 'X';
				$number -= 10;
			}

			while (9 <= $number) {
				$roman .= 'IX';
				$number -= 9;
			}

			while (5 <= $number) {
				$roman .= 'V';
				$number -= 5;
			}

			while (4 <= $number) {
				$roman .= 'IV';
				$number -= 4;
			}

			while (1 <= $number) {
				$roman .= 'I';
				--$number;
			}

			return $roman;
		}

		protected function putHtmlListBullet($listdepth, $listtype = '', $size = 10)
		{
			$size /= $this->k;
			$fill = '';
			$color = $this->fgcolor;
			$width = 0;
			$textitem = '';
			$tmpx = $this->x;
			$lspace = $this->GetStringWidth('  ');

			if ($listtype == '!') {
				$deftypes = array('disc', 'circle', 'square');
				$listtype = $deftypes[($listdepth - 1) % 3];
			}
			else if ($listtype == '#') {
				$listtype = 'decimal';
			}

			switch ($listtype) {
			case 'none':
				break;

			case 'disc':
				$fill = 'F';
			case 'circle':
				$fill .= 'D';
				$r = $size / 6;
				$lspace += 2 * $r;

				if ($this->rtl) {
					$this->x = $this->w - $this->x - $lspace;
				}
				else {
					$this->x -= $lspace;
				}

				$this->Circle($this->x + $r, $this->y + ($this->lasth / 2), $r, 0, 360, $fill, array('color' => $color), $color, 8);
				break;

			case 'square':
				$l = $size / 3;
				$lspace += $l;

				if ($this->rtl) {
					$this->x = $this->w - $this->x - $lspace;
				}
				else {
					$this->x -= $lspace;
				}

				$this->Rect($this->x, $this->y + (($this->lasth - $l) / 2), $l, $l, 'F', array(), $color);
				break;

			case '1':
			case 'decimal':
				$textitem = $this->listcount[$this->listnum];
				break;

			case 'decimal-leading-zero':
				$textitem = sprintf('%02d', $this->listcount[$this->listnum]);
				break;

			case 'i':
			case 'lower-roman':
				$textitem = strtolower($this->intToRoman($this->listcount[$this->listnum]));
				break;

			case 'I':
			case 'upper-roman':
				$textitem = $this->intToRoman($this->listcount[$this->listnum]);
				break;

			case 'a':
			case 'lower-alpha':
			case 'lower-latin':
				$textitem = chr((97 + $this->listcount[$this->listnum]) - 1);
				break;

			case 'A':
			case 'upper-alpha':
			case 'upper-latin':
				$textitem = chr((65 + $this->listcount[$this->listnum]) - 1);
				break;

			case 'lower-greek':
				$textitem = $this->unichr((945 + $this->listcount[$this->listnum]) - 1);
				break;

			default:
				$textitem = $this->listcount[$this->listnum];
			}

			if (!$this->empty_string($textitem)) {
				if ($this->rtl) {
					$textitem = '.' . $textitem;
				}
				else {
					$textitem = $textitem . '.';
				}

				$lspace += $this->GetStringWidth($textitem);

				if ($this->rtl) {
					$this->x += $lspace;
				}
				else {
					$this->x -= $lspace;
				}

				$this->Write($this->lasth, $textitem, '', false, '', false, 0, false);
			}

			$this->x = $tmpx;
			$this->lispacer = '';
		}

		protected function getGraphicVars()
		{
			$grapvars = array('FontFamily' => $this->FontFamily, 'FontStyle' => $this->FontStyle, 'FontSizePt' => $this->FontSizePt, 'rMargin' => $this->rMargin, 'lMargin' => $this->lMargin, 'cMargin' => $this->cMargin, 'LineWidth' => $this->LineWidth, 'linestyleWidth' => $this->linestyleWidth, 'linestyleCap' => $this->linestyleCap, 'linestyleJoin' => $this->linestyleJoin, 'linestyleDash' => $this->linestyleDash, 'DrawColor' => $this->DrawColor, 'FillColor' => $this->FillColor, 'TextColor' => $this->TextColor, 'ColorFlag' => $this->ColorFlag, 'bgcolor' => $this->bgcolor, 'fgcolor' => $this->fgcolor, 'htmlvspace' => $this->htmlvspace, 'lasth' => $this->lasth);
			return $grapvars;
		}

		protected function setGraphicVars($gvars)
		{
			$this->FontFamily = $gvars['FontFamily'];
			$this->FontStyle = $gvars['FontStyle'];
			$this->FontSizePt = $gvars['FontSizePt'];
			$this->rMargin = $gvars['rMargin'];
			$this->lMargin = $gvars['lMargin'];
			$this->cMargin = $gvars['cMargin'];
			$this->LineWidth = $gvars['LineWidth'];
			$this->linestyleWidth = $gvars['linestyleWidth'];
			$this->linestyleCap = $gvars['linestyleCap'];
			$this->linestyleJoin = $gvars['linestyleJoin'];
			$this->linestyleDash = $gvars['linestyleDash'];
			$this->DrawColor = $gvars['DrawColor'];
			$this->FillColor = $gvars['FillColor'];
			$this->TextColor = $gvars['TextColor'];
			$this->ColorFlag = $gvars['ColorFlag'];
			$this->bgcolor = $gvars['bgcolor'];
			$this->fgcolor = $gvars['fgcolor'];
			$this->htmlvspace = $gvars['htmlvspace'];
			$this->_out('' . $this->linestyleWidth . ' ' . $this->linestyleCap . ' ' . $this->linestyleJoin . ' ' . $this->linestyleDash . ' ' . $this->DrawColor . ' ' . $this->FillColor . '');

			if (!$this->empty_string($this->FontFamily)) {
				$this->SetFont($this->FontFamily, $this->FontStyle, $this->FontSizePt);
			}
		}

		protected function getObjFilename($name)
		{
			return tempnam(K_PATH_CACHE, $name . '_');
		}

		protected function writeDiskCache($filename, $data, $append = false)
		{
			if ($append) {
				$fmode = 'ab+';
			}
			else {
				$fmode = 'wb+';
			}

			$f = @fopen($filename, $fmode);

			if (!$f) {
				$this->Error('Unable to write cache file: ' . $filename);
			}
			else {
				fwrite($f, $data);
				fclose($f);
			}

			if (!isset($this->cache_file_lenght['_' . $filename])) {
				$this->cache_file_lenght['_' . $filename] = strlen($data);
			}
			else {
				$this->cache_file_lenght['_' . $filename] += strlen($data);
			}
		}

		protected function readDiskCache($filename)
		{
			return file_get_contents($filename);
		}

		protected function setBuffer($data)
		{
			$this->bufferlen += strlen($data);

			if ($this->diskcache) {
				if (!isset($this->buffer) || $this->empty_string($this->buffer)) {
					$this->buffer = $this->getObjFilename('buffer');
				}

				$this->writeDiskCache($this->buffer, $data, true);
			}
			else {
				$this->buffer .= $data;
			}
		}

		protected function getBuffer()
		{
			if ($this->diskcache) {
				return $this->readDiskCache($this->buffer);
			}
			else {
				return $this->buffer;
			}
		}

		protected function setPageBuffer($page, $data, $append = false)
		{
			if ($this->diskcache) {
				if (!isset($this->pages[$page])) {
					$this->pages[$page] = $this->getObjFilename('page' . $page);
				}

				$this->writeDiskCache($this->pages[$page], $data, $append);
			}
			else if ($append) {
				$this->pages[$page] .= $data;
			}
			else {
				$this->pages[$page] = $data;
			}

			if ($append && isset($this->pagelen[$page])) {
				$this->pagelen[$page] += strlen($data);
			}
			else {
				$this->pagelen[$page] = strlen($data);
			}
		}

		protected function getPageBuffer($page)
		{
			if ($this->diskcache) {
				return $this->readDiskCache($this->pages[$page]);
			}
			else if (isset($this->pages[$page])) {
				return $this->pages[$page];
			}

			return false;
		}

		protected function setImageBuffer($image, $data)
		{
			if ($this->diskcache) {
				if (!isset($this->images[$image])) {
					$this->images[$image] = $this->getObjFilename('image' . $image);
				}

				$this->writeDiskCache($this->images[$image], serialize($data));
			}
			else {
				$this->images[$image] = $data;
			}

			if (!in_array($image, $this->imagekeys)) {
				$this->imagekeys[] = $image;
			}

			++$this->numimages;
		}

		protected function setImageSubBuffer($image, $key, $data)
		{
			if (!isset($this->images[$image])) {
				$this->setImageBuffer($image, array());
			}

			if ($this->diskcache) {
				$tmpimg = $this->getImageBuffer($image);
				$tmpimg[$key] = $data;
				$this->writeDiskCache($this->images[$image], serialize($tmpimg));
			}
			else {
				$this->images[$image][$key] = $data;
			}
		}

		protected function getImageBuffer($image)
		{
			if ($this->diskcache && isset($this->images[$image])) {
				return unserialize($this->readDiskCache($this->images[$image]));
			}
			else if (isset($this->images[$image])) {
				return $this->images[$image];
			}

			return false;
		}

		protected function setFontBuffer($font, $data)
		{
			if ($this->diskcache) {
				if (!isset($this->fonts[$font])) {
					$this->fonts[$font] = $this->getObjFilename('font');
				}

				$this->writeDiskCache($this->fonts[$font], serialize($data));
			}
			else {
				$this->fonts[$font] = $data;
			}

			if (!in_array($font, $this->fontkeys)) {
				$this->fontkeys[] = $font;
			}
		}

		protected function setFontSubBuffer($font, $key, $data)
		{
			if (!isset($this->fonts[$font])) {
				$this->setFontBuffer($font, array());
			}

			if ($this->diskcache) {
				$tmpfont = $this->getFontBuffer($font);
				$tmpfont[$key] = $data;
				$this->writeDiskCache($this->fonts[$font], serialize($tmpfont));
			}
			else {
				$this->fonts[$font][$key] = $data;
			}
		}

		protected function getFontBuffer($font)
		{
			if ($this->diskcache && isset($this->fonts[$font])) {
				return unserialize($this->readDiskCache($this->fonts[$font]));
			}
			else if (isset($this->fonts[$font])) {
				return $this->fonts[$font];
			}

			return false;
		}

		public function movePage($frompage, $topage)
		{
			if (($this->numpages < $frompage) || ($frompage <= $topage)) {
				return false;
			}

			if ($frompage == $this->page) {
				$this->endPage();
			}

			$tmppage = $this->pages[$frompage];
			$tmppagedim = $this->pagedim[$frompage];
			$tmppagelen = $this->pagelen[$frompage];
			$tmpintmrk = $this->intmrk[$frompage];

			if (isset($this->footerpos[$frompage])) {
				$tmpfooterpos = $this->footerpos[$frompage];
			}

			if (isset($this->footerlen[$frompage])) {
				$tmpfooterlen = $this->footerlen[$frompage];
			}

			if (isset($this->transfmrk[$frompage])) {
				$tmptransfmrk = $this->transfmrk[$frompage];
			}

			if (isset($this->PageAnnots[$frompage])) {
				$tmpannots = $this->PageAnnots[$frompage];
			}

			if (isset($this->newpagegroup[$frompage])) {
				$tmpnewpagegroup = $this->newpagegroup[$frompage];
			}

			for ($i = $frompage; $topage < $i; --$i) {
				$j = $i - 1;
				$this->pages[$i] = $this->pages[$j];
				$this->pagedim[$i] = $this->pagedim[$j];
				$this->pagelen[$i] = $this->pagelen[$j];
				$this->intmrk[$i] = $this->intmrk[$j];

				if (isset($this->footerpos[$j])) {
					$this->footerpos[$i] = $this->footerpos[$j];
				}
				else if (isset($this->footerpos[$i])) {
					unset($this->footerpos[$i]);
				}

				if (isset($this->footerlen[$j])) {
					$this->footerlen[$i] = $this->footerlen[$j];
				}
				else if (isset($this->footerlen[$i])) {
					unset($this->footerlen[$i]);
				}

				if (isset($this->transfmrk[$j])) {
					$this->transfmrk[$i] = $this->transfmrk[$j];
				}
				else if (isset($this->transfmrk[$i])) {
					unset($this->transfmrk[$i]);
				}

				if (isset($this->PageAnnots[$j])) {
					$this->PageAnnots[$i] = $this->PageAnnots[$j];
				}
				else if (isset($this->PageAnnots[$i])) {
					unset($this->PageAnnots[$i]);
				}

				if (isset($this->newpagegroup[$j])) {
					$this->newpagegroup[$i] = $this->newpagegroup[$j];
				}
				else if (isset($this->newpagegroup[$i])) {
					unset($this->newpagegroup[$i]);
				}
			}

			$this->pages[$topage] = $tmppage;
			$this->pagedim[$topage] = $tmppagedim;
			$this->pagelen[$topage] = $tmppagelen;
			$this->intmrk[$topage] = $tmpintmrk;

			if (isset($tmpfooterpos)) {
				$this->footerpos[$topage] = $tmpfooterpos;
			}
			else if (isset($this->footerpos[$topage])) {
				unset($this->footerpos[$topage]);
			}

			if (isset($tmpfooterlen)) {
				$this->footerlen[$topage] = $tmpfooterlen;
			}
			else if (isset($this->footerlen[$topage])) {
				unset($this->footerlen[$topage]);
			}

			if (isset($tmptransfmrk)) {
				$this->transfmrk[$topage] = $tmptransfmrk;
			}
			else if (isset($this->transfmrk[$topage])) {
				unset($this->transfmrk[$topage]);
			}

			if (isset($tmpannots)) {
				$this->PageAnnots[$topage] = $tmpannots;
			}
			else if (isset($this->PageAnnots[$topage])) {
				unset($this->PageAnnots[$topage]);
			}

			if (isset($tmpnewpagegroup)) {
				$this->newpagegroup[$topage] = $tmpnewpagegroup;
			}
			else if (isset($this->newpagegroup[$topage])) {
				unset($this->newpagegroup[$topage]);
			}

			$tmpoutlines = $this->outlines;

			foreach ($tmpoutlines as $key => $outline) {
				if (($topage <= $outline['p']) && ($outline['p'] < $frompage)) {
					$this->outlines[$key]['p'] = $outline['p'] + 1;
				}
				else if ($outline['p'] == $frompage) {
					$this->outlines[$key]['p'] = $topage;
				}
			}

			$tmplinks = $this->links;

			foreach ($tmplinks as $key => $link) {
				if (($topage <= $link[0]) && ($link[0] < $frompage)) {
					$this->links[$key][0] = $link[0] + 1;
				}
				else if ($link[0] == $frompage) {
					$this->links[$key][0] = $topage;
				}
			}

			$tmpjavascript = $this->javascript;
			global $jfrompage;
			global $jtopage;
			$jfrompage = $frompage;
			$jtopage = $topage;
			$this->javascript = preg_replace_callback('/this\\.addField\\(\'([^\']*)\',\'([^\']*)\',([0-9]+)/', create_function('$matches', 'global $jfrompage, $jtopage;' . "\n" . '				$pagenum = intval($matches[3]) + 1;' . "\n" . '				if (($pagenum >= $jtopage) AND ($pagenum < $jfrompage)) {' . "\n" . '					$newpage = ($pagenum + 1);' . "\n" . '				} elseif ($pagenum == $jfrompage) {' . "\n" . '					$newpage = $jtopage;' . "\n" . '				} else {' . "\n" . '					$newpage = $pagenum;' . "\n" . '				}' . "\n" . '				--$newpage;' . "\n" . '				return "this.addField(\'".$matches[1]."\',\'".$matches[2]."\',".$newpage."";'), $tmpjavascript);
			$this->lastPage(true);
			return true;
		}

		public function deletePage($page)
		{
			if ($this->numpages < $page) {
				return false;
			}

			unset($this->pages[$page]);
			unset($this->pagedim[$page]);
			unset($this->pagelen[$page]);
			unset($this->intmrk[$page]);

			if (isset($this->footerpos[$page])) {
				unset($this->footerpos[$page]);
			}

			if (isset($this->footerlen[$page])) {
				unset($this->footerlen[$page]);
			}

			if (isset($this->transfmrk[$page])) {
				unset($this->transfmrk[$page]);
			}

			if (isset($this->PageAnnots[$page])) {
				unset($this->PageAnnots[$page]);
			}

			if (isset($this->newpagegroup[$page])) {
				unset($this->newpagegroup[$page]);
			}

			if (isset($this->pageopen[$page])) {
				unset($this->pageopen[$page]);
			}

			for ($i = $page; $i < $this->numpages; ++$i) {
				$j = $i + 1;
				$this->pages[$i] = $this->pages[$j];
				$this->pagedim[$i] = $this->pagedim[$j];
				$this->pagelen[$i] = $this->pagelen[$j];
				$this->intmrk[$i] = $this->intmrk[$j];

				if (isset($this->footerpos[$j])) {
					$this->footerpos[$i] = $this->footerpos[$j];
				}
				else if (isset($this->footerpos[$i])) {
					unset($this->footerpos[$i]);
				}

				if (isset($this->footerlen[$j])) {
					$this->footerlen[$i] = $this->footerlen[$j];
				}
				else if (isset($this->footerlen[$i])) {
					unset($this->footerlen[$i]);
				}

				if (isset($this->transfmrk[$j])) {
					$this->transfmrk[$i] = $this->transfmrk[$j];
				}
				else if (isset($this->transfmrk[$i])) {
					unset($this->transfmrk[$i]);
				}

				if (isset($this->PageAnnots[$j])) {
					$this->PageAnnots[$i] = $this->PageAnnots[$j];
				}
				else if (isset($this->PageAnnots[$i])) {
					unset($this->PageAnnots[$i]);
				}

				if (isset($this->newpagegroup[$j])) {
					$this->newpagegroup[$i] = $this->newpagegroup[$j];
				}
				else if (isset($this->newpagegroup[$i])) {
					unset($this->newpagegroup[$i]);
				}

				if (isset($this->pageopen[$j])) {
					$this->pageopen[$i] = $this->pageopen[$j];
				}
				else if (isset($this->pageopen[$i])) {
					unset($this->pageopen[$i]);
				}
			}

			unset($this->pages[$this->numpages]);
			unset($this->pagedim[$this->numpages]);
			unset($this->pagelen[$this->numpages]);
			unset($this->intmrk[$this->numpages]);

			if (isset($this->footerpos[$this->numpages])) {
				unset($this->footerpos[$this->numpages]);
			}

			if (isset($this->footerlen[$this->numpages])) {
				unset($this->footerlen[$this->numpages]);
			}

			if (isset($this->transfmrk[$this->numpages])) {
				unset($this->transfmrk[$this->numpages]);
			}

			if (isset($this->PageAnnots[$this->numpages])) {
				unset($this->PageAnnots[$this->numpages]);
			}

			if (isset($this->newpagegroup[$this->numpages])) {
				unset($this->newpagegroup[$this->numpages]);
			}

			if (isset($this->pageopen[$this->numpages])) {
				unset($this->pageopen[$this->numpages]);
			}

			--$this->numpages;
			$this->page = $this->numpages;
			$tmpoutlines = $this->outlines;

			foreach ($tmpoutlines as $key => $outline) {
				if ($page < $outline['p']) {
					$this->outlines[$key]['p'] = $outline['p'] - 1;
				}
				else if ($outline['p'] == $page) {
					unset($this->outlines[$key]);
				}
			}

			$tmplinks = $this->links;

			foreach ($tmplinks as $key => $link) {
				if ($page < $link[0]) {
					$this->links[$key][0] = $link[0] - 1;
				}
				else if ($link[0] == $page) {
					unset($this->links[$key]);
				}
			}

			$tmpjavascript = $this->javascript;
			global $jpage;
			$jpage = $page;
			$this->javascript = preg_replace_callback('/this\\.addField\\(\'([^\']*)\',\'([^\']*)\',([0-9]+)/', create_function('$matches', 'global $jpage;' . "\n" . '				$pagenum = intval($matches[3]) + 1;' . "\n" . '				if ($pagenum >= $jpage) {' . "\n" . '					$newpage = ($pagenum - 1);' . "\n" . '				} elseif ($pagenum == $jpage) {' . "\n" . '					$newpage = 1;' . "\n" . '				} else {' . "\n" . '					$newpage = $pagenum;' . "\n" . '				}' . "\n" . '				--$newpage;' . "\n" . '				return "this.addField(\'".$matches[1]."\',\'".$matches[2]."\',".$newpage."";'), $tmpjavascript);
			$this->lastPage(true);
			return true;
		}

		public function addTOC($page = '', $numbersfont = '', $filler = '.')
		{
			$fontsize = $this->FontSizePt;
			$fontfamily = $this->FontFamily;
			$fontstyle = $this->FontStyle;
			$w = $this->w - $this->lMargin - $this->rMargin;
			$spacer = $this->GetStringWidth(' ') * 4;
			$page_first = $this->getPage();
			$lmargin = $this->lMargin;
			$rmargin = $this->rMargin;
			$x_start = $this->GetX();

			if ($this->empty_string($numbersfont)) {
				$numbersfont = $this->default_monospaced_font;
			}

			if ($this->empty_string($filler)) {
				$filler = ' ';
			}

			if ($this->empty_string($page)) {
				$gap = ' ';
			}
			else {
				$gap = '';
			}

			foreach ($this->outlines as $key => $outline) {
				if ($this->rtl) {
					$aligntext = 'R';
					$alignnum = 'L';
				}
				else {
					$aligntext = 'L';
					$alignnum = 'R';
				}

				if ($outline['l'] == 0) {
					$this->SetFont($fontfamily, $fontstyle . 'B', $fontsize);
				}
				else {
					$this->SetFont($fontfamily, $fontstyle, $fontsize - $outline['l']);
				}

				$indent = $spacer * $outline['l'];

				if ($this->rtl) {
					$this->rMargin += $indent;
					$this->x -= $indent;
				}
				else {
					$this->lMargin += $indent;
					$this->x += $indent;
				}

				$link = $this->AddLink();
				$this->SetLink($link, 0, $outline['p']);
				$this->Write(0, $outline['t'], $link, 0, $aligntext, false, 0, false, false, 0);
				$this->SetFont($numbersfont, $fontstyle, $fontsize);

				if ($this->empty_string($page)) {
					$pagenum = $outline['p'];
				}
				else {
					$pagenum = '{#' . $outline['p'] . '}';
					if (($this->CurrentFont['type'] == 'TrueTypeUnicode') || ($this->CurrentFont['type'] == 'cidfont0')) {
						$pagenum = '{' . $pagenum . '}';
					}
				}

				$numwidth = $this->GetStringWidth($pagenum);

				if ($this->rtl) {
					$tw = $this->x - $this->lMargin;
				}
				else {
					$tw = $this->w - $this->rMargin - $this->x;
				}

				$fw = $tw - $numwidth - $this->GetStringWidth(' ');
				$numfills = floor($fw / $this->GetStringWidth($filler));

				if (0 < $numfills) {
					$rowfill = str_repeat($filler, $numfills);
				}
				else {
					$rowfill = '';
				}

				if ($this->rtl) {
					$pagenum = $pagenum . $gap . $rowfill . ' ';
				}
				else {
					$pagenum = ' ' . $rowfill . $gap . $pagenum;
				}

				$this->Cell($tw, 0, $pagenum, 0, 1, $alignnum, 0, $link, 0);
				$this->SetX($x_start);
				$this->lMargin = $lmargin;
				$this->rMargin = $rmargin;
			}

			$page_last = $this->getPage();
			$numpages = ($page_last - $page_first) + 1;

			if (!$this->empty_string($page)) {
				for ($p = $page_first; $p <= $page_last; ++$p) {
					$temppage = $this->getPageBuffer($p);

					for ($n = 1; $n <= $this->numpages; ++$n) {
						$k = '{#' . $n . '}';
						$ku = '{' . $k . '}';
						$alias_a = $this->_escape($k);
						$alias_au = $this->_escape('{' . $k . '}');

						if ($this->isunicode) {
							$alias_b = $this->_escape($this->UTF8ToLatin1($k));
							$alias_bu = $this->_escape($this->UTF8ToLatin1($ku));
							$alias_c = $this->_escape($this->utf8StrRev($k, false, $this->tmprtl));
							$alias_cu = $this->_escape($this->utf8StrRev($ku, false, $this->tmprtl));
						}

						if ($page <= $n) {
							$np = $n + $numpages;
						}
						else {
							$np = $n;
						}

						$ns = $this->formatTOCPageNumber($np);
						$nu = $ns;
						$sdiff = strlen($k) - strlen($ns) - 1;
						$sdiffu = strlen($ku) - strlen($ns) - 1;
						$sfill = str_repeat($filler, $sdiff);
						$sfillu = str_repeat($filler, $sdiffu);

						if ($this->rtl) {
							$ns = $ns . ' ' . $sfill;
							$nu = $nu . ' ' . $sfillu;
						}
						else {
							$ns = $sfill . ' ' . $ns;
							$nu = $sfillu . ' ' . $nu;
						}

						$nu = $this->UTF8ToUTF16BE($nu, false);
						$temppage = str_replace($alias_au, $nu, $temppage);

						if ($this->isunicode) {
							$temppage = str_replace($alias_bu, $nu, $temppage);
							$temppage = str_replace($alias_cu, $nu, $temppage);
							$temppage = str_replace($alias_b, $ns, $temppage);
							$temppage = str_replace($alias_c, $ns, $temppage);
						}

						$temppage = str_replace($alias_a, $ns, $temppage);
					}

					$this->setPageBuffer($p, $temppage);
				}

				for ($i = 0; $i < $numpages; ++$i) {
					$this->movePage($page_last, $page);
				}
			}

			$this->SetFont($fontfamily, $fontstyle, $fontsize);
		}

		public function startTransaction()
		{
			if (isset($this->objcopy)) {
				$this->commitTransaction();
			}

			$this->start_transaction_page = $this->page;
			$this->objcopy = $this->objclone($this);
		}

		public function commitTransaction()
		{
			if (isset($this->objcopy)) {
				$this->objcopy->_destroy(true, true);
				unset($this->objcopy);
			}
		}

		public function rollbackTransaction($self = false)
		{
			if (isset($this->objcopy)) {
				if (isset($this->objcopy->diskcache) && $this->objcopy->diskcache) {
					foreach ($this->objcopy->cache_file_lenght as $file => $lenght) {
						$file = substr($file, 1);
						$handle = fopen($file, 'r+');
						ftruncate($handle, $lenght);
					}
				}

				$this->_destroy(true, true);

				if ($self) {
					$objvars = get_object_vars($this->objcopy);

					foreach ($objvars as $key => $value) {
						$this->$key = $value;
					}
				}

				return $this->objcopy;
			}

			return $this;
		}

		public function objclone($object)
		{
			return @clone $object;
		}

		public function empty_string($str)
		{
			return is_null($str) || (is_string($str) && (strlen($str) == 0));
		}
	}
}

?>
