var g_spouts = [];

var Sketch = class{
	constructor(){
		this.coms = [];
		this.int_ins = [];
		this.int_outs = [];
	}

	appendCom(com){
		this.coms.push(com);
	}
	removeCom(rm){
		this.coms = this.coms.filter((com) => { return com!=rm; });
	}
	clearCom(){
		while(this.coms.length) this.removeCom(this.coms[0]);
	}

	onSimStart(){
		this.coms.forEach((com) => {
			com.onSimStart();
		});
	}

	onChangeTime(e){
		var chcoms = [];
		this.coms.forEach((com) => {
			Array.prototype.push.apply(chcoms, com.onChangeTime(e));
		});
		while(chcoms.length){
			chcoms = chcoms.filter((chcom, i) => { return i==chcoms.indexOf(chcom); });
			var com = chcoms.shift();
			Array.prototype.push.apply(chcoms, com.onChangeIn());
		}
	}

	onSimEnd(){
		this.coms.forEach((com) => {
			com.onSimEnd();
		});
	}

	upInterface(){
		var int_ins = this.int_ins;
		var int_outs = this.int_outs;
		int_ins = [];
		int_outs = [];
		this.coms.forEach((com) => {
			int_ins = int_ins.concat(com.int_ins);
			int_outs = int_outs.concat(com.int_outs);
		});
	}

	export(){
		var ex = {};
		ex.coms = [];
		this.coms.forEach((com) => {
			ex.coms.push(com.export());
		});
		return ex;
	}

	import(im){
		this.clearCom();
		var lut = [];
		im.coms.forEach((im) => {
			var com = eval("new "+im.type+"()");
			lut = lut.concat(com.import(im));
			this.appendCom(com);
		});

		var comlut = [];
		this.coms.forEach((com) => {
			com.connectById(lut);
			comlut.push({ id: com.id, inst: com });
		});
		return comlut;
	}
};

var Component = class{
	constructor(){
		this.ins = [];
		this.outs = [];
		this.ui_class = UiComponent;
		this.id = UUID.generate();
		this._loopcnt = 0;
	}

	appendIn(in_){
		this.ins.push(in_);
		in_.com = this;
	}
	removeIn(rm){
		this.ins = this.ins.filter((in_) => { return in_!=rm; });
		rm.com = null;
	}
	clearIn(){
		while(this.ins.length) this.removeIn(this.ins[0]);
	}

	appendOut(out){
		this.outs.push(out);
	}
	removeOut(rm){
		this.outs = this.outs.filter((out) => { return out!=rm; });
	}
	clearOut(){
		while(this.outs.length) this.removeOut(this.outs[0]);
	}

	initPort(in_n, out_n){
		this.clearIn();
		for(var i = 0; i<in_n; i++){
			this.appendIn(new PortIn());
		}

		this.clearOut();
		for(var i = 0; i<out_n; i++){
			this.appendOut(new PortOut());
		}
	}

	update(){
		var chins = [];
		this.outs.forEach((out) => {
			Array.prototype.push.apply(chins, out.update())
		});

		var chcoms = [];
		chins.forEach((in_) => {
			chcoms.push(in_.com);
		});
		return chcoms;
		/*chcoms = chcoms.filter((chcom, i) => { return i==chcoms.indexOf(chcom); });

		chcoms.forEach((chcom) => {
			chcom.onChangeIn();
		});*/
	}

	get int_ins(){
		var int_ins = [];

		this.ins.forEach((in_) => {
			if (in_.isint) int_ins.push(in_);
		});
		return int_ins;
	}

	get int_outs(){
		var int_outs = [];

		this.outs.forEach((out) => {
			if (out.isint) int_outs.push(out);
		});
		return int_outs;
	}

	export(){
		var ex = {};
		ex.type = this.constructor.name;
		ex.id = this.id;

		ex.ins = [];
		this.ins.forEach((in_) => {
			ex.ins.push(in_.export());
		});

		ex.outs = [];
		this.outs.forEach((out) => {
			ex.outs.push(out.export());
		});

		return ex;
	}

	import(im){
		var lut = [];

		this.id = im.id;
		this.clearIn();
		im.ins.forEach((im) => {
			var in_ = new PortIn();
			lut.push(in_.import(im));
			this.appendIn(in_);
		});

		this.clearOut();
		im.outs.forEach((im) => {
			var out = new PortOut();
			out.import(im);
			this.appendOut(out);
		});

		return lut;
	}

	connectById(lut){
		this.outs.forEach((out) => {
			out.connectById(lut);
		});
	}

	onSimStart(){}
	onChangeIn(){
		if (++this._loopcnt>=256){
			throw "An infinite loop was occured. \nPlease insert \"Buffer\" to prevent it. ";
		}
		return [];
	}
	onChangeTime(){
		this._loopcnt = 0;
		return [];
	}
	onSimEnd(){}
};

var PortIn = class{
	constructor(){
		this.com = null;
		this.val = 0.0;
		this.src = null;
		this.isint = false;
		this.id = UUID.generate();
	}

	connect(src){
		this.disconnect();
		this.src = src;
		src.tos.push(this);
	}

	disconnect(){
		if (this.src){
			var dis = this;
			this.src.tos = this.src.tos.filter((to) => { return to!=dis; });
			this.src = null;
		}
	}

	export(){
		return {id: this.id, isint: this.isint, };
	}

	import(im){
		this.id = im.id;
		this.isint = im.isint;
		return { id: this.id, inst: this };
	}
};

var PortOut = class{
	constructor(){
		this.tos = [];
		this.to_ids = [];
		this._latch = 0.0;
		this._val = 0.0;
		this.isint = false;
	}

	get val(){
		return this._val;
	}

	set latch(value){
		this._latch = value;
	}

	update(){
		if (!isFinite(this._latch)){
			throw "An port value was diverged. ";
		}
		if (this._val != this._latch){
			this._val = this._latch;

			this.tos.forEach((to) => {
				to.val = this._val;
			});

			return this.tos;
		}else{
			return [];
		}
	}

	disconnectAll(){
		while(this.tos.length) this.tos[0].disconnect(this);
	}

	export(){
		var tos = [];
		this.tos.forEach((to) => {
			tos.push(to.id);
		});
		return {tos: tos, isint: this.isint, };
	}

	import(im){
		this.to_ids = im.tos;
		this.isint = im.isint;
	}

	connectById(lut){
		this.disconnectAll();
		this.to_ids.forEach((to_id) => {
			for(var i = 0; i<lut.length; i++){
				if (to_id==lut[i].id){
					lut[i].inst.connect(this);
					break;
				}
			}
		});
	}
};

var Custom = class extends Component{
	constructor(){
		super();

		this.phase = 0.0;
		this._sk = null;
	}

	set sketch(value){
		this._sk = value;

		this._sk.upInterface();
		this.initPort(this._sk.int_ins.length, this._sk.int_outs.length);
	}

	update(){
		if (this._sk){
			var outs = this.outs;
			this._sk.int_outs.forEach((int_out, i) => {
				outs[i].latch = int_out.val;
			});
		}
		super.update();
	}

	onSimStart(){
		if (this._sk){
			this._sk.onSimStart();
		}
		this.update();
	}

	onChangeIn(){
		if (this._sk){
			var ins = this.ins;
			var chins = [];
			this._sk.int_ins.forEach((int_in, i) => {
				if (int_in.val!=ins[i].val){
					int_in.val = ins[i].val;
					chins.push(int_in);
				}
			});

			var chcoms = [];
			chins.forEach((in_) => {
				chcoms.push(in_.com);
			});
			chcoms = chcoms.filter((chcom, i) => { return i==chcoms.indexOf(chcom); });

			chcoms.forEach((chcom) => {
				chcom.onChangeIn();
			});
		}
		this.update();
	}

	onChangeTime(e){
		if (this._sk){
			this._sk.onChangeTime(e);
		}
		this.update();
	}

	onSimEnd(){
		if (this._sk){
			this._sk.onSimEnd();
		}
		this.update();
	}
}
