var g_JDIframeWidth = 400;
var g_JDIframeHeight = 300;
var g_JDNormalMode = true;
var g_JDCallBackFun = null;
var JqueryDialog = {
	cBackgroundColor: "#ffffff",
	cBorderSize: 2,
	cBorderColor: "#999999",
	cHeaderBackgroundColor: "#f0f0f0",
	cCloseText: "",
	cCloseTitle: "关闭",
	cBottomBackgroundColor: "#f0f0f0",
	cSubmitText: "确 认",
	cCancelText: "取 消",
	cDragTime: "100",
	Open: function(b, e, a, d, c) {
		JDdebug("Open");
		g_JDCallBackFun = c;
		JqueryDialog.init(b, e, a, d, true, true, true);
	},
	Open1: function(b, g, a, f, c, e, d) {
		JDdebug("Open1");
		JqueryDialog.init(b, g, a, f, c, e, d);
	},
	init: function(c, n, i, g, m, k, b) {
		JDdebug("init");
		var l = document.body.clientWidth;
		var d = document.documentElement.scrollHeight;
		if (typeof($("#jd_shadow")[0]) == "undefined") {
			$("body").prepend("<div id='jd_shadow'>&nbsp;</div>");
			ResizeShadow();
			JDdebug("create shadow");
			JDdebug("width : " + l - 1 + "px");
			JDdebug("height : " + document.documentElement.clientHeight + "px");
		}
		g_JDIframeWidth = i;
		g_JDIframeHeight = g;
		JDdebug("title : " + c);
		JDdebug("iframeSrc : " + n);
		JDdebug("iframeWidth : " + i);
		JDdebug("iframeHeight : " + g);
		JDdebug("isSubmitButton : " + m);
		JDdebug("isCancelButton : " + k);
		JDdebug("isDrag : " + b);
		if (typeof($("#jd_dialog")[0]) != "undefined") {
			$("#jd_dialog").remove();
		}
		$("body").prepend("<div id='jd_dialog'></div>");
		var q = $("#jd_dialog");
		var p = (l - (i + JqueryDialog.cBorderSize * 2 + 5)) / 2;
		q.css("left", (p < 0 ? 0: p) + document.documentElement.scrollLeft + "px");
		var o = (document.documentElement.clientHeight - (g + JqueryDialog.cBorderSize * 2 + 30 + 50 + 5)) / 2;
		q.css("top", (o < 0 ? 0: o) + document.documentElement.scrollTop + "px");
		q.append("<div id='jd_dialog_s'>&nbsp;</div>");
		var e = $("#jd_dialog_s");
		e.css("width", i + JqueryDialog.cBorderSize * 2 + "px");
		e.css("height", g + JqueryDialog.cBorderSize * 2 + 30 + 50 + "px");
		q.append("<div id='jd_dialog_m'></div>");
		var h = $("#jd_dialog_m");
		h.css("border", JqueryDialog.cBorderColor + " " + JqueryDialog.cBorderSize + "px solid");
		h.css("width", i + "px");
		h.css("background-color", JqueryDialog.cBackgroundColor);
		h.append("<div id='jd_dialog_m_h'></div>");
		var f = $("#jd_dialog_m_h");
		f.css("background-color", JqueryDialog.cHeaderBackgroundColor);
		f.append("<span id='jd_dialog_m_h_l'>" + c + "</span>");
		f.append("<span id='jd_dialog_m_h_r' title='" + JqueryDialog.cCloseTitle + "' onclick='JqueryDialog.Close();'>" + JqueryDialog.cCloseText + "</span>");
		f.append("<span id='jd_dialog_m_h_rn' title='还原窗体' style='display:none;' onclick='JqueryDialog.ShowNormal(true);'></span>");
		f.append("<span id='jd_dialog_m_h_rr' title='自适应大小' onclick='JqueryDialog.Maximized(true);'></span>");
		
		h.append("<div id='jd_dialog_m_b'></div>");
		var j = $("#jd_dialog_m_b");
		j.css("width", i + "px");
		j.css("height", g + "px");
		j.append("<div id='jd_dialog_m_b_1'>&nbsp;</div>");
		var a = $("#jd_dialog_m_b_1");
		a.css("top", "30px");
		a.css("width", i + "px");
		a.css("height", g + "px");
		a.css("display", "none");
		j.append("<div id='jd_dialog_m_b_2'></div>");
		$("#jd_dialog_m_b_2").append("<iframe id='jd_iframe' src='" + n + "' frameborder='0' width='" + i + "' height='" + g + "' />");
		if (b) {
			DragAndDrop.Register(q[0], f[0]);
		}
	},
	Close: function(a) {
		JDdebug("Close");
		$("#jd_shadow").remove();
		$("#jd_dialog").remove();
		if (g_JDCallBackFun) {
			g_JDCallBackFun(a);
		}
	},
	Maximized: function(c) {
		JDdebug("Maximized");
		if ($("#jd_dialog_m_b").length > 0) {
			var a = GetTotalWidth() - 80;
			$("#jd_dialog")[0].style.left = "40px";
			$("#jd_dialog")[0].style.top = "20px";
			$("#jd_dialog_m")[0].style.width = a + "px";
			$("#jd_dialog_m iframe")[0].style.width = a + "px";
			$("#jd_dialog_m_b")[0].style.width = a + "px";
			var b = GetTotalHeight() - 80;
			$("#jd_dialog_m")[0].style.height = b + "px";
			$("#jd_dialog_m iframe")[0].style.height = b + "px";
			$("#jd_dialog_m_b")[0].style.height = b + "px";
			g_JDNormalMode = false;
		}
		if (c) {
			$("#jd_dialog_m_h_rn")[0].style.display = "";
			$("#jd_dialog_m_h_rr")[0].style.display = "none";
		}
	},
	ShowNormal: function(d) {
		JDdebug("ShowNormal");
		if ($("#jd_dialog_m_b").length > 0) {
			var a = g_JDIframeWidth;
			$("#jd_dialog")[0].style.left = (GetTotalWidth() - g_JDIframeWidth) / 2 + "px";
			var b = (GetTotalHeight() - g_JDIframeHeight) / 2;
			if (b < 20) {
				b = 20;
			}
			$("#jd_dialog")[0].style.top = b + "px";
			$("#jd_dialog_m")[0].style.width = a + "px";
			$("#jd_dialog_m iframe")[0].style.width = a + "px";
			$("#jd_dialog_m_b")[0].style.width = a + "px";
			var c = g_JDIframeHeight;
			$("#jd_dialog_m")[0].style.height = c + "px";
			$("#jd_dialog_m iframe")[0].style.height = c + "px";
			$("#jd_dialog_m_b")[0].style.height = c + "px";
			g_JDNormalMode = true;
		}
		if (d) {
			$("#jd_dialog_m_h_rn")[0].style.display = "none";
			$("#jd_dialog_m_h_rr")[0].style.display = "";
		}
	},
	ResizeModalWindow: function() {
		JDdebug("ResizeModalWindow");
		if (g_JDNormalMode) {
			JqueryDialog.ShowNormal();
		} else {
			JqueryDialog.Maximized();
		}
	},
	Ok: function() {
		JDdebug("Ok");
		var a = $("#jd_iframe");
		if (a[0].contentWindow.Ok()) {
			JqueryDialog.Close();
		} else {
			a[0].focus();
		}
	},
	SubmitCompleted: function(b, c, a) {
		JDdebug("SubmitCompleted");
		if ($.trim(b).length > 0) {
			alert(b);
		}
		if (c) {
			JqueryDialog.Close();
			if (a) {
				window.location.href = window.location.href;
			}
		}
	}
};
var DragAndDrop = function() {
	var l;
	var h;
	var g;
	var c;
	var e = false;
	var a;
	var d;
	var f = function(m) {
		return m.ownerDocument || m.document;
	};
	var b = function(m) {
		if (c) {
			m = m || window.event;
			l = document.body.clientWidth;
			h = document.documentElement.scrollHeight;
			$("#jd_dialog_m_b_1").css("display", "");
			e = true;
			a = {
				x: $(c).offset().left,
				y: $(c).offset().top
			};
			d = {
				x: m.screenX,
				y: m.screenY
			};
			$(document).bind("mousemove", k);
			$(document).bind("mouseup", j);
			if (m.preventDefault) {
				m.preventDefault();
			} else {
				m.returnValue = false;
			}
		}
	};
	var k = function(m) {
		if (e) {
			m = m || window.event;
			var n = {
				x: m.screenX,
				y: m.screenY
			};
			a.x = a.x + (n.x - d.x);
			a.y = a.y + (n.y - d.y);
			d = n;
			$(c).css("left", a.x + "px");
			$(c).css("top", a.y + "px");
			if (m.preventDefault) {
				m.preventDefault();
			} else {
				m.returnValue = false;
			}
		}
	};
	var j = function(m) {
		if (e) {
			m = m || window.event;
			$("#jd_dialog_m_b_1").css("display", "none");
			i();
			e = false;
		}
	};
	var i = function() {
		if (g) {
			$(g.document).unbind("mousemove");
			$(g.document).unbind("mouseup");
		}
	};
	return {
		Register: function(m, n) {
			c = m;
			g = n;
			$(g).bind("mousedown", b);
		}
	};
} ();
function JDdebug(a) {}
function ResizeShadow() {
	var a = $("#jd_shadow");
	a.css("width", GetTotalWidth() + "px");
	a.css("height", GetTotalHeightEx() + "px");
	JqueryDialog.ResizeModalWindow();
}
function GetTotalHeight() {
	if ($.browser.msie) {
		return document.compatMode == "CSS1Compat" ? document.documentElement.clientHeight: document.body.clientHeight;
	} else {
		return self.innerHeight;
	}
}
function GetTotalHeightEx() {
	if ($.browser.msie) {
		return document.compatMode == "CSS1Compat" ? document.documentElement.clientHeight: document.body.clientHeight;
	} else {
		return self.innerHeight;
	}
}
function GetTotalWidth() {
	if ($.browser.msie) {
		return document.compatMode == "CSS1Compat" ? document.documentElement.clientWidth: document.body.clientWidth;
	} else {
		return self.innerWidth;
	}
}
function FrmJsRoot() {
	var root,
		tmp;
		var scripts=document.getElementsByTagName("script");
		for (var i = 0; i < scripts.length; i++) {
			root = scripts[i].getAttribute("src");
			root = (root || "").substr(0, root.toLowerCase().indexOf("divframe.js"));
			tmp = root.lastIndexOf("/");
			if (tmp > 0) root = root.substring(0, tmp + 1);
			if (root) break
		}
		return root
}

function frmIncludeCSS(link) {
	var head = document.getElementsByTagName("head")[0];
		var css = document.createElement("link");
		css.rel = "stylesheet";
		css.href = link;
		css.type = "text/css";
		head.appendChild(css);
}

$(document).ready(function() {
	frmIncludeCSS(FrmJsRoot()+"cssdivfrm.css");//加载css
	$(window).resize(function() {
		JDdebug("resize");
		ResizeShadow();
	});
});
function showModalRefresh(b, c, a, d) {
	JqueryDialog.Open(d, b, c, a, JDCloseCallbackFun);
}
function JDCloseCallbackFun() {
	window.location.reload();
}
function showModalTemplate(b, d, c, a, e) {
	JqueryDialog.Open(d, b, c, a, e);
}