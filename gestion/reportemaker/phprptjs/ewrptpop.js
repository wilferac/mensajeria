// Popup Filter for PHP Report Maker 5
// (C) 2006-2011 e.World Technology Ltd.

var EWRPT_POPUP_MINWIDTH = 200;
var EWRPT_POPUP_DEFAULTHEIGHT = 200;
var EWRPT_EMPTY_VALUE = "##empty##";
var EWRPT_NULL_VALUE = "##null##";

// Create a dialog instance for the popup filter DIV 'ewrpt_PopupFilter'
var ewrptPopupFilter = new ewrptWidget.Dialog("ewrpt_PopupFilter", {
	draggable: false, close: false,
	width: EWRPT_POPUP_MINWIDTH + "px",
	height: EWRPT_POPUP_DEFAULTHEIGHT + "px",
	constraintoviewport: true,
	visible: false,
	postmethod: "post"		
});
ewrptPopupFilter.render();

// Create Resize instance, binding it to the popup filter DIV
var ewrptPopupFilterResize = new ewrptUtil.Resize("ewrpt_PopupFilter", {
	handles: ["br"],
	autoRatio: false,
	minWidth: EWRPT_POPUP_MINWIDTH,
	minHeight: EWRPT_POPUP_DEFAULTHEIGHT,
	status: false 
});

// Setup startResize handler, to constrain the resize width/height
// if the constraintoviewport configuration property is enabled.
ewrptPopupFilterResize.on("startResize", function(args) {	
	if (this.cfg.getProperty("constraintoviewport")) {
		var clientRegion = ewrptDom.getClientRegion();
		var elRegion = ewrptDom.getRegion(this.element);	
		ewrptPopupFilterResize.set("maxWidth", clientRegion.right - elRegion.left - ewrptWidget.Overlay.VIEWPORT_OFFSET);
		ewrptPopupFilterResize.set("maxHeight", clientRegion.bottom - elRegion.top - ewrptWidget.Overlay.VIEWPORT_OFFSET);
	} else {
		ewrptPopupFilterResize.set("maxWidth", null);
		ewrptPopupFilterResize.set("maxHeight", null);
	}	
}, ewrptPopupFilter, true);

// Setup resize handler to update the dialog's 'height' configuration property 
// whenever the size of the popup filter DIV DIV changes.

// Setting the height configuration property will result in the 
// body of the Panel being resized to fill the new height and the iframe shim
// and shadow being resized also if required (for IE6 and IE7 quirks mode).
ewrptPopupFilterResize.on("resize", function(args) {
	var dlgHeight = args.height;
	this.cfg.setProperty("height", dlgHeight + "px");
}, ewrptPopupFilter, true);	

// popup object
var ewrpt_Popups = {};

// Create a popup filter
function ewrpt_CreatePopup(name, data) {
	ewrpt_Popups[name] = data;
}

// Show popup filter
function ewrpt_ShowPopup(anchorname, popupname, useRange, rangeFrom, rangeTo) {
	var data = ewrpt_Popups[popupname];
	if (data) {
		ewrpt_SetPopupContent(popupname, data, useRange, rangeFrom, rangeTo);
		var cfg = { context: [anchorname, "tl", "bl"],
			buttons: [ { text:ewLanguage.Phrase("PopupOK"), handler:function(){
				if (!ewrpt_SelectedEntry(this.form, popupname)) {
					alert(ewLanguage.Phrase("PopupNoValue"));
				} else {
					this.form.submit();
					this.hide();
				}
			}, isDefault:true },
			{ text:ewLanguage.Phrase("PopupCancel"), handler:function(){this.cancel();} } ]
		};
		if (ewrptEnv.ua.ie && ewrptEnv.ua.ie >= 8)
			cfg["underlay"] = "none";
		ewrptPopupFilter.cfg.applyConfig(cfg);
		ewrptPopupFilter.render();
		ewrptPopupFilter.show();
	}	
}

// Hide popup filter
function ewrpt_HidePopup(popupname) {
	ewrptPopupFilter.hide();
}

// Set popup fitler content
function ewrpt_SetPopupContent(name, data, useRange, rangeFrom, rangeTo) {
	var selectall = true;
	var showdivider = false;
	var datacnt = data.length;	
	var sb = new StringBuilder();
	for (var i=0; i<datacnt; i++)
		selectall = data[i][2] ? selectall : false;
	var checkedall = selectall ? " checked=\"checked\"" : "";
	var html = "<form id=\"" + name + "_FilterForm\" method=\"post\">";
	html += "<input type=\"hidden\" name=\"popup\" value=\"" + name + "\" />";
	html += "<table style=\"border: 0px; border-collapse: collapse;\">";
	html += "<tr><td style=\"background-color: White; white-space: nowrap;\">";
	if (useRange) {
		var selected;
		html += "<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"phpreportmaker\">";
		html += "<tr><td>" + ewLanguage.Phrase("PopupFrom") + "</td><td>";
		html += "<select name=\"rf_" + name + "\" onchange=\"ewrpt_SelectRange(this.form, '" + name + "');\">";
		html += "<option value=\"\">" + ewLanguage.Phrase("PopupSelect") + "</option>";
		sb.clear();
		for (var i=0; i<datacnt; i++) {
			if (data[i][0].substring(0,2)!="@@" && data[i][0]!=EWRPT_NULL_VALUE && data[i][0]!=EWRPT_EMPTY_VALUE) {
				selected = (data[i][0]==rangeFrom) ? " selected=\"selected\"" : "";
				sb.append("<option value=\"" + data[i][0] + "\"" + selected + ">" + data[i][1] + "</option>");
			}
		}
		html += sb.toString();
		html += "</select></td></tr>";
		html += "<tr><td>" + ewLanguage.Phrase("PopupTo") + "</td><td>";
		html += "<select name=\"rt_" + name + "\" onchange=\"ewrpt_SelectRange(this.form, '" + name + "');\">";
		html += "<option value=\"\">" + ewLanguage.Phrase("PopupSelect") + "</option>";
		sb.clear();
		for (var i=0; i<datacnt; i++) {
			if (data[i][0].substring(0,2)!="@@" && data[i][0]!=EWRPT_NULL_VALUE && data[i][0]!=EWRPT_EMPTY_VALUE) {
				selected = (data[i][0]==rangeTo) ? " selected=\"selected\"" : "";
				sb.append("<option value=\"" + data[i][0] + "\"" + selected + ">" + data[i][1] + "</option>");
			}
		}
		html += sb.toString();
		html += "</select></td></tr></table>";
	}
	html += "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"phpreportmaker\"><tr><td>";
	html += "<input type=\"checkbox\" name=\"sel_" + name + "[]\" value=\"\" onclick=\"ewrpt_SelectAll(this);\"" + checkedall + " />" + ewLanguage.Phrase("PopupAll") + "<br />";
	sb.clear();	
	for (var i=0; i<datacnt; i++) {
		var checked = data[i][2] ? " checked=\"checked\"" : "";
		if (data[i][0].substring(0,2)=="@@")
			showdivider = true;
		else if (showdivider) {
			showdivider = false; sb.append("<hr class=\"ewPopupHorizRule\" />");
		}
		sb.append("<input type=\"checkbox\" name=\"sel_" + name + "[]\" value=\"" + data[i][0] + "\" onclick=\"ewrpt_UpdateSelectAll(this);\"" + checked + " />" + data[i][1] + "<br />");
	}
	html += sb.toString();
	html += "</td></tr></table>";
	html += "</td></tr>";
	html += "</table>";
	html += "</form>";
	ewrptPopupFilter.setBody(html);
}

// Check if selected
function ewrpt_SelectedEntry(f,name) {
	var elemname = "sel_" + name + "[]";
	if (!f.elements[elemname]) return false;
	if (f.elements[elemname][0]) {
		for (var i=1; i<f.elements[elemname].length; i++) {
			if (f.elements[elemname][i].checked)
				return true;
		}
	} else {
		return f.elements[elemname].checked;
	}
	return false;
}

// Select range
function ewrpt_SelectRange(f, name) {
	var rangeFrom, rangeTo;
	var elemname = "rf_" + name;
	rangeFrom = f.elements[elemname].options[f.elements[elemname].selectedIndex].value;
	var elemname = "rt_" + name;
	rangeTo = f.elements[elemname].options[f.elements[elemname].selectedIndex].value;
	if ((rangeFrom == null) || (rangeFrom == "") || (rangeTo == null) || (rangeTo == ""))
		return;
	elemname = "sel_" + name + "[]";
	ewrpt_SetRange(f, elemname, rangeFrom, rangeTo, true);
}

// Clear range
function ewrpt_ClearRange(elem) {
	var fromname, toname, rangeFrom, rangeTo;
	var f = elem.form;
	var elemname = elem.name;
	var name = elemname.substring(4, elemname.length-2); // remove "sel_" and "[]"
	fromname = "rf_" + name;
	toname = "rt_" + name;
	if (f.elements[fromname] && f.elements[toname]) {
		if (f.elements[fromname].selectedIndex > 0 && f.elements[toname].selectedIndex > 0) {
			rangeFrom = f.elements[fromname].options[f.elements[fromname].selectedIndex].value;
			rangeTo = f.elements[toname].options[f.elements[toname].selectedIndex].value;
			f.elements[fromname].selectedIndex = 0;
			f.elements[toname].selectedIndex = 0;
			ewrpt_SetRange(f, elemname, rangeFrom, rangeTo, false);
		}
	}
}

// Set range
function ewrpt_SetRange(f, elemname, rangeFrom, rangeTo, set) {
	var bInRange = false;
	if (!f.elements[elemname]) return;
	if (f.elements[elemname][0]) {
		for (var i=0; i<f.elements[elemname].length; i++) {
			if (f.elements[elemname][i].value == rangeFrom) bInRange = true;
			if (bInRange)
				f.elements[elemname][i].checked = bInRange && set;
			else
				if (set) f.elements[elemname][i].checked = false;
			if (f.elements[elemname][i].value == rangeTo) bInRange = false;
		}
	} else {
		if (set)
			f.elements[elemname].checked = ((f.elements[elemname].value == rangeFrom) ||
							(f.elements[elemname].value == rangeTo));
	}
}

// Select all
function ewrpt_SelectAll(elem) {
	var f = elem.form;
	var elemname = elem.name;
	if (!f.elements[elemname]) return;
	ewrpt_ClearRange(elem); // clear any range set
	if (f.elements[elemname][0]) {
		for (var i=0; i<f.elements[elemname].length; i++)
			f.elements[elemname][i].checked = elem.checked;	
	} else {
		f.elements[elemname].checked = elem.checked;	
	}
}

// Update select all
function ewrpt_UpdateSelectAll(elem) {
	var f = elem.form;
	var elemname = elem.name;	
	if (!f.elements[elemname]) return;
	ewrpt_ClearRange(elem); // clear any range set
	var allChecked = true;
	if (f.elements[elemname][0]) {
		for (var i=1; i<f.elements[elemname].length; i++) {
			if (!f.elements[elemname][i].checked) { 
				allChecked = false;
				break;
			}	
		}
		f.elements[elemname][0].checked = allChecked;
	}	
}

// Initializes a new instance of the StringBuilder class
// and appends the given value if supplied
function StringBuilder(value) {
	this.strings = new Array("");
	this.append(value);
}

// Appends the given value to the end of this instance.
StringBuilder.prototype.append = function(value) {
	if (value)
		this.strings.push(value);
}

// Clears the string buffer
StringBuilder.prototype.clear = function() {
	this.strings.length = 1;
}

// Converts this instance to a String.
StringBuilder.prototype.toString = function() {
	return this.strings.join("");
}
