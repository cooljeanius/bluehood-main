var Editor = class{
	static get max_z(){
		return ++Editor._max_z;
	}

	constructor(jqobj, jqside, coms, sk){
		this.sk = sk;
		this._uicoms = [];
		this._jqobj = jqobj;
		this._jqside = jqside;
		this._jqsel = null;
		this._issel = false;
		this._prevent_sel = false;
		this._sel1x = this._sel1y = 0;
		this._sel2x = this._sel2y = 0;
		this._selcoms = [];
		this._selcomposs = [];

		this.sk.clearCom();
		this._jqside.html('<h3 style="display: inline; font-size: inherit; font-weight: inherit; ">Components</h3><br>');

		var add = () => {
			var com = eval("new "+listbox.val()+"()");
			this.sk.appendCom(com);

			var uicom = new com.ui_class(com, this);
			uicom.initDom();
			uicom.x = this._jqobj.scrollLeft() + Editor.border_left;
			uicom.y = this._jqobj.scrollTop() + Editor.border_top;

			this.appendUiComponent(uicom);
		}

		var listbox = $('<select style="overflow-y: hidden; margin-bottom: 1ex; "></select>');
		coms.forEach((class_, i) => {
			var option = $('<option style="cursor: pointer; "></option>');
			option.dblclick((e) => {
				add();
			});
			option.append(class_.name);
			listbox.append(option);
		});
		listbox.attr("size", coms.length);
		this._jqside.append(listbox);

		var jqaddbtn = $('<br><button style="width: 100%; ">Add ＋</button>');
		jqaddbtn.click((e) => {
			add();
		});
		this._jqside.append(jqaddbtn);

		var jqviewbtn = $('<br><button style="width: 100%; ">Document 📄</button>');
		jqviewbtn.click((e) => {
			window.open("doc/"+listbox.val()+"/");
		});
		this._jqside.append(jqviewbtn);

		var jq_selevent = $(window);
		this._jqsel = $('<div style="position: absolute; border: 1px dotted gray; z-index: 65536; "></div>');
		var jqpointer = $('<span id="pointer" style="position: absolute; "></span>');
		jq_selevent.on("mousedown", (e) => {
			if (!this._prevent_sel){
				var org_e = e.originalEvent;
				this._sel1x = this._sel2x = org_e.clientX + jq_selevent.scrollLeft();
				this._sel1y = this._sel2y = org_e.clientY + jq_selevent.scrollTop();

				this._jqsel.width(0).height(0);
				this._jqsel.css("left", 0);
				this._issel = true;
				this._jqsel.show();
			}
			this._prevent_sel = false;
		});
		jq_selevent.on("mousemove", (e) => {
			var org_e = e.originalEvent;
			if (this._issel){
				this._sel2x = org_e.clientX + jq_selevent.scrollLeft();
				this._sel2y = org_e.clientY + jq_selevent.scrollTop();

				this._jqsel.offset({left: Math.min(this._sel1x, this._sel2x), top: Math.min(this._sel1y, this._sel2y)});
				this._jqsel.width(Math.abs(this._sel1x - this._sel2x)).height(Math.abs(this._sel1y - this._sel2y));
			}
		});
		jq_selevent.on("mouseup", (e) => {
			if (this._issel){
				this._selcoms = [];
				this._selcomposs = [];
				this._uicoms.forEach((uicom) => {
					if (uicom.select(Math.min(this._sel1x, this._sel2x), Math.min(this._sel1y, this._sel2y),
						Math.max(this._sel1x, this._sel2x), Math.max(this._sel1y, this._sel2y)))
					{
						this._selcoms.push(uicom);
						this._selcomposs.push({x: uicom.x, y: uicom.y});
					}
				});

				this._issel = false;
				this._jqsel.hide();
			}
		});
		jq_selevent.on("drag", (e) => {
			var org_e = e.originalEvent;
			jqpointer.offset({left: org_e.clientX + jq_selevent.scrollLeft(), top: org_e.clientY + jq_selevent.scrollTop()});
		});
		this._jqobj.append(this._jqsel);
		this._jqobj.append(jqpointer);

		Editor.border_left = this._jqside.outerWidth();
		Editor.border_top = this._jqside.offset().top;
	}

	preventSel(){
		this._prevent_sel = true;
	}

	selComDispose(){
		this._selcoms.forEach((selcom) => {
			this.sk.removeCom(selcom.com);
			selcom.dispose();
		});
		this._selcoms = [];
	}

	selComReverse(){
		this._selcoms.forEach((selcom) => {
			selcom.reverse();
		});
	}

	selComDragStart(){
		this._selcoms.forEach((selcom) => {
			selcom.jqobj.css("z-index", Editor.max_z);
		});
	}

	selComDrag(orgcom){
		var org_i = this._selcoms.indexOf(orgcom);
		this._selcoms.forEach((selcom, i) => {
			selcom.x = orgcom.x + (this._selcomposs[i].x - this._selcomposs[org_i].x) ;
			selcom.y = orgcom.y + (this._selcomposs[i].y - this._selcomposs[org_i].y) ;
			selcom.onMove();
		});
	}

	selComDragStop(){
		this._selcoms.forEach((selcom) => {
			var offset = selcom.jqobj.offset();
			selcom.x = offset.left ;
			selcom.y = offset.top ;
			selcom.onMove();
		});
	}

	appendUiComponent(uicom){
		//this.sk.appendCom(uicom.com);
		this._uicoms.push(uicom);
		this._jqobj.append(uicom.jqobj);
	}
	removeUiComponent(rm){
		//this.sk.removeCom(rm.com);
		var is_dispose = this._uicoms.filter((uicom) => { return uicom==rm; }).length;
		this._uicoms = this._uicoms.filter((uicom) => { return uicom!=rm; });
		if (is_dispose) rm.dispose();
	}
	clearUiComponent(){
		while(this._uicoms.length) this.removeUiComponent(this._uicoms[0]);
	}

	export(){
		var ex = {};

		var uicoms = [];
		this._uicoms.forEach((uicom) => {
			uicoms.push(uicom.export());
		});
		ex.uicoms = uicoms;
		return ex;
	}

	import(im, lut){
		this.clearUiComponent();
		im.uicoms.forEach((im) => {
			var com = this.sk.coms.filter((com) => { return com.id==im.com_id; })[0];
			var uicom = new com.ui_class(com, this);
			uicom.import(im);
			this.appendUiComponent(uicom);
		});

		this._uicoms.forEach((uicom) => {
			uicom.initConnection();
		});
	}

}
Editor._max_z = 0;
Editor.dragobj = null;
Editor.border_left = 0;
Editor.border_top = 0;

var UiComponent = class{
	constructor(com, uisk){
		this.com = com;
		this.jqobj = null;
		this._jqname = null;
		this._uiins = [];
		this._uiouts = [];
		this._isrev = false;
		this._uisk = uisk;
		this._issel = false;

		this.jqobj = $('<table style="position: absolute; border-collapse: collapse; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4); background-color: white; border: 1px solid lightgray; cursor: move; font-size: small; "></table>');
		this._jqname = $('<input style="border: none; background-color: inherit; text-align: center; width: 96px; font-size: inherit; ">');
		this.name = this.com.constructor.name;

		this.com.ins.forEach((in_, i) => {
			var uiin = new UiPortIn(in_, this._isrev);
			uiin.name = Object.keys(this.com.In)[i];
			this._uiins.push(uiin);
		});

		this.com.outs.forEach((out, i) => {
			var uiout = new UiPortOut(out, this._isrev);
			uiout.name = Object.keys(this.com.Out)[i];
			this._uiouts.push(uiout);
		});
	}

	initDom(){
		this.jqobj.empty();
		{
			var column;
			{
				var reverse = $('<span style="padding: 0 0.25em; cursor: default; ">🔁</span>');
				reverse.mousedown((e) => {
					if (this._issel) this._uisk.selComReverse();
					else this.reverse();
				});
				var close = $('<span style="padding: 0 0.25em; cursor: default; ">❌</span>');
				close.mousedown((e) => {
					if (this._issel) this._uisk.selComDispose();
					else{
						this._uisk.sk.removeCom(this.com);
						this.dispose();
					}
				});
				var right = $('<span></span>');
				right.append(reverse).append(close);
				column = $('<tr style="border-bottom: 1px solid lightgray; "></tr>').append($('<th colspan="2" style="font-weight: normal; padding-left: 0.5em; padding-right: 0.5em; background-color: lemonchiffon"></th>').append(this._jqname).append(right));
			}
			this.jqobj.append(column);
			if (!this._isrev){
				column = $('<tr style="text-align: center; "><td>In</td><td>Out</td></tr>');
			}else{
				column = $('<tr style="text-align: center; "><td>Out</td><td>In</td></tr>');
			}
			this.jqobj.append(column);

			for(var i = 0; i<this._uiins.length || i<this._uiouts.length; i++){
				var jqin = $("<td></td>");
				if (i<this._uiins.length){
					jqin.append(this._uiins[i].jqobj);
				}

				var jqout = $('<td></td>');
				if (i<this._uiouts.length){
					jqout.append(this._uiouts[i].jqobj);
				}

				if (!this._isrev){
					jqout.css("text-align", "right");
					column = $('<tr></tr>').append(jqin).append(jqout);
				}else{
					jqin.css("text-align", "right");
					column = $('<tr></tr>').append(jqout).append(jqin);
				}
				this.jqobj.append(column);
			}

			this._uiins.forEach((uiin) => { uiin.initEvent(); });
			this._uiouts.forEach((uiout) => { uiout.initEvent(); });
		}
		this.jqobj.draggable({
			start: (e) => {
				if (this._issel){
					this._uisk.selComDragStart();
				}else{
					this.jqobj.css("z-index", Editor.max_z);
				}
			},
			drag: (e) => {
				if (this._issel){
					this._uisk.selComDrag(this);
				}else{
					this.onMove();
				}
			},
			stop: () => {
				if (this._issel){
					this._uisk.selComDragStop();
				}else{
					var offset = this.jqobj.offset();
					this.x = offset.left ;
					this.y = offset.top ;
					this.onMove();
				}
			},
		});
		this.jqobj.on("mousedown", (e) => {
			this._uisk.preventSel();
		});

		this.onMove();
	}

	initConnection(){
		this._uiouts.forEach((uiout) => {
			uiout.initConnection();
		});
	}

	set x(value){
		var x = Math.max(value, Editor.border_left);
		this.jqobj.css("left", x.toString());
	}
	get x(){
		return this.jqobj.offset().left;
	}

	set y(value){
		var y = Math.max(value, Editor.border_top);
		this.jqobj.css("top", y.toString());
	}
	get y(){
		return this.jqobj.offset().top;
	}

	set name(value){
		this._jqname.val(value);
	}
	get name(){
		return this._jqname.val();
	}

	onMove(){
		this._uiins.forEach((uiin) => {
			uiin.onMove();
		});
		this._uiouts.forEach((uiout) => {
			uiout.onMove();
		});
	}

	reverse(){
		this._isrev ^= true;
		this._uiins.forEach((uiin) => {
			uiin.reverse();
		});
		this._uiouts.forEach((uiout) => {
			uiout.reverse();
		});
		this.initDom();
	}

	set issel(value){
		this._issel = value;

		if (this._issel){
			this.jqobj.css("border", "2px solid gray");
		}else{
			this.jqobj.css("border", "1px solid lightgray");
		}
	}

	select(sel1x, sel1y, sel2x, sel2y){
		if (this.x>=sel1x && this.y>=sel1y && this.x + this.jqobj.width()<sel2x && this.y + this.jqobj.height()<sel2y){
			this.issel = true;
			return true;
		}
		this.issel = false;
		return false;
	}

	export(){
		var ex = {};
		ex.com_id = this.com.id;
		ex.x = this.x;
		ex.y = this.y;
		ex.name = this.name;
		ex.isrev = this._isrev;
		return ex;
	}

	import(im){
		this.initDom();
		this.x = im.x;
		this.y = im.y;
		this.name = im.name;
		if (im.isrev) this.reverse();
	}

	dispose(){
		this._uiins.forEach((uiin) => {
			uiin.dispose();
		});

		this._uiouts.forEach((uiout) => {
			uiout.dispose();
		});

		this._uisk.removeUiComponent(this);
		this.jqobj.remove();
		delete this;
	}
}

var UiPortIn = class{
	constructor(in_, isrev){
		this.in_ = in_;
		this.jqobj = null;
		this._jqname = null;
		this._jqradio = null;
		this.src = null;
		this.id = "";
		this._isrev = isrev;

		this.in_.ui = this;

		this.id = UUID.generate();
		this._jqname = $('<input readonly style="width: 40px; border: none; text-align: inherit; ">');
		this._jqradio = $('<input class="inport" type="checkbox">');
		this._jqradio.attr("id", this.id);
		this.jqobj = $("<div></div>");

		this.initDom();
	}

	initDom(){
		this.jqobj.empty();
		if (!this._isrev){
			this.jqobj.append(this._jqradio);
			this.jqobj.append(this._jqname);
		}else{
			this.jqobj.append(this._jqname);
			this.jqobj.append(this._jqradio);
		}
	}

	initEvent(){
		this._jqradio.on("dragover", (e) => {
			var org_e = e.originalEvent;
			if (org_e.dataTransfer.types.includes("application/uiportout")){
				org_e.dataTransfer.dropEffect = "link";
				org_e.preventDefault();
			}
		});
		this._jqradio.on("drop", (e) => {
			var org_e = e.originalEvent;
			if (org_e.dataTransfer.types.includes("application/uiportout")){
				this.connect(Editor.dragobj);
			}
		});
		this._jqradio.click((e) => {
			this.disconnect();
			e.preventDefault();
		});
	}

	onMove(){
		if (this.src) this.src.onMove();
	}

	connect(src){
		this.disconnect();
		this.src = src;
		this.src.appendTo(this);
		this.in_.connect(src.out);
	}

	disconnect(){
		if (this.src){
			var rm = this.src.tos.filter((to) => { return to.uiin==this; })[0];
			this.src.tos = this.src.tos.filter((to) => { return to!=rm; });
			rm.arrow.remove();

			this.src = null;
			this.in_.disconnect();
		}
	}

	reverse(){
		this._isrev ^= true;
		this.initDom();
	}

	set name(value){
		this._jqname.val(value);
	}

	dispose(){
		this.disconnect();
		this.jqobj.remove();
		delete this;
	}
};

var UiPortOut = class{
	constructor(out, isrev){
		this.out = out;
		this.jqobj = null;
		this._jqname = null;
		this._jqradio = null;
		this.id = "";
		this.tos = [];
		this._isrev = isrev;
		this._dragarrow = null;

		this.id = UUID.generate();
		this._jqname = $('<input readonly style="width: 40px; border: none; text-align: inherit; ">');
		this._jqradio = $('<input class="outport" type="radio" draggable="true">');
		this._jqradio.attr("id", this.id);

		this.jqobj = $("<div></div>");

		this.initDom();
	}

	initDom(){
		this.jqobj.empty();
		if (!this._isrev){
			this.jqobj.append(this._jqname);
			this.jqobj.append(this._jqradio);
		}else{
			this.jqobj.append(this._jqradio);
			this.jqobj.append(this._jqname);
		}
	}

	initEvent(){
		this._jqradio.on("dragstart", (e) => {
			var org_e = e.originalEvent;
			org_e.dataTransfer.setData("application/uiportout", "");
			Editor.dragobj = this;
			org_e.dataTransfer.effectAllowed = "link";

		});
		this._jqradio.on("drag", (e) => {
			if (!this._dragarrow){
				if ($("#pointer").offset().left){
					this._dragarrow = new LeaderLine(
						document.getElementById(this.id),
						document.getElementById("pointer"),
						{
							color: "lightpink",
							size: 2,
						}
					);
				}
			}
			if (this._dragarrow) this._dragarrow.position();
		});
		this._jqradio.on("dragend", (e) => {
			this._dragarrow.remove();
			this._dragarrow = null;
		});

		this._jqradio.on("dragover", (e) => {
			var org_e = e.originalEvent;
			org_e.dataTransfer.dropEffect = "none";
			org_e.preventDefault();
		});

		this._jqradio.click((e) => {
			e.preventDefault();
		});
	}

	initConnection(){
		this.clearTo();
		this.out.tos.forEach((to) => {
			to.ui.connect(this);
		});
	}

	onMove(){
		this.tos.forEach((to) => {
			to.arrow.position();
		});
	}

	appendTo(to){
		var arrow = new LeaderLine(
			document.getElementById(this.id),
			document.getElementById(to.id),
			{
				color: "lightpink",
				size: 2,
				//path: "grid",
			}
		);
		this.tos.push({uiin: to, arrow: arrow});
	}

	removeTo(rmin){
		rmin.disconnect();
	}

	clearTo(){
		while(this.tos.length) this.removeTo(this.tos[0].uiin);
	}

	reverse(){
		this._isrev ^= true;
		this.initDom();
	}

	set name(value){
		this._jqname.val(value);
	}

	dispose(){
		this.clearTo();
		this.jqobj.remove();
		delete this;
	}
};
