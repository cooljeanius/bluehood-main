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
		super.onChangeIn();
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
		super.onChangeTime(e);
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

var Speaker = class extends Component{
	get In(){ return {sound: 0, }; }
	get Out(){ return {thru: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
	}

	onChangeTime(e){
		super.onChangeTime(e);
		g_spouts.push(this.outs[this.Out.thru].latch = this.ins[this.In.sound].val);
		return this.update();
	}
};

var Input = class extends Component{
	get In(){ return {}; }
	get Out(){ return {value: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
		this.ui_class = UiInput;
		this._val = 0.0;
		this._i = 0;
	}

	onChangeTime(e){
		super.onChangeTime(e);
		if (this._i++%65536==0){
			this.outs[this.Out.value].latch = 0.0;
			this.update();
		}
		this.outs[this.Out.value].latch = this._val;
		return this.update();
	}

	set value(value){
		this._val = value;
	}
};

var UiInput = class extends UiComponent{
	constructor(com, uisk){
		super(com, uisk);
		this._jqinput = $('<input style="width: 112px; text-align: inherit; " placeholder="(ex.) 440.0">');
	}

	initDom(){
		super.initDom();
		var column = $('<td colspan="2" style="text-align: center; "></td>');
		column.append(this._jqinput);
		this.jqobj.append($('<tr></tr>').append(column));

		this._jqinput.change(() => {
			this.com.value = this.value;
		});
	}

	set value(value){
		this.com.value = value;
		this._jqinput.val(value);
	}
	get value(){
		return Number(this._jqinput.val());
	}

	export(){
		var ex = super.export();
		ex.value = this.value;
		return ex;
	}

	import(im){
		super.import(im);
		this.value = im.value;
	}
};

var Keyboard = class extends Component{
	get In(){ return { }; }
	get Out(){ return {freq: 0, keyon: 1}; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
		this.ui_class = UiKeyboard;
	}

	onChangeTime(e){
		super.onChangeTime(e);
		return this.update();
	}

	keyon(key){
		var freq = 0.0;
		switch(key){
			case 81:
			freq = 261.626;	// 4C
			break;
			case 50:
			freq = 277.183;	// C#
			break;
			case 87:
			freq = 293.665;	// D
			break;
			case 51:
			freq = 311.127;	//D#
			break;
			case 69:
			freq = 329.628;	// E
			break;
			case 82:
			freq = 349.228;	// F
			break;
			case 53:
			freq = 369.994;	// F#
			break;
			case 84:
			freq = 391.995;	// G
			break;
			case 54:
			freq = 415.305;	// G#
			break;
			case 89:
			freq = 440.0;	// A
			break;
			case 55:
			freq = 466.164;	// A#
			break;
			case 85:
			freq = 493.883;	// B
			break;
			case 73:
			freq = 523.251;	// 5C
			break;

			case 90:
			freq = 130.813;	// Z 3C
			break;
			case 83:
			freq = 138.591;	// S C#
			break;
			case 88:
			freq = 146.832;	// X D
			break;
			case 68:
			freq = 155.563;	//D D#
			break;
			case 67:
			freq = 164.814;	// C E
			break;
			case 86:
			freq = 174.614;	// V F
			break;
			case 71:
			freq = 184.997;	// G F#
			break;
			case 66:
			freq = 195.998;	// B G
			break;
			case 72:
			freq = 207.652;	// H G#
			break;
			case 78:
			freq = 220.000;	// N A
			break;
			case 74:
			freq = 233.082;	// J A#
			break;
			case 77:
			freq = 246.942;	// M B
			break;
			case 188:
			freq = 261.626;	// < 4C
			break;
		}
		if (freq!=0.0){
			this.outs[this.Out.freq].latch = freq;
			this.outs[this.Out.keyon].latch = 1.0;
		}
		//this.update();
	}

	keyoff(){
		this.outs[this.Out.keyon].latch = 0.0;
		//this.update();
	}
};

var UiKeyboard = class extends UiComponent{
	constructor(com, uisk){
		super(com, uisk);
		this._key = 0;

		$(window).keydown((e) => {
			this._key = e.keyCode;
			this.com.keyon(e.keyCode);
		});
		$(window).keyup((e) => {
			if (e.keyCode==this._key) this.com.keyoff();
		});
	}
};

var Amplifier = class extends Component{
	get In(){ return {in_1: 0, in_2: 1}; }
	get Out(){ return {amp: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
	}

	onChangeIn(){
		super.onChangeIn();
		this.outs[this.Out.amp].latch = this.ins[this.In.in_1].val*this.ins[this.In.in_2].val;
		return this.update();
	}
};

var Mixer = class extends Component{
	get In(){ return {in_1: 0, in_2: 1}; }
	get Out(){ return {mix: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
	}

	onChangeIn(){
		super.onChangeIn();
		this.outs[this.Out.mix].latch = this.ins[this.In.in_1].val+this.ins[this.In.in_2].val;
		return this.update();
	}
};

var Integrator = class extends Component{
	get In(){ return {in_: 0}; }
	get Out(){ return {int: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
		this._int = 0.0;
	}

	onSimStart(){
		this._int = 0.0;
	}

	onChangeTime(e){
		super.onChangeTime(e);
		this._int += this.ins[this.In.in_].val*e.dt;
		this.outs[this.Out.int].latch = this._int;
		return this.update();
	}
};

var Differentiator = class extends Component{
	get In(){ return {in_: 0}; }
	get Out(){ return {diff: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
		this._prev = 0.0;
	}

	onSimStart(){
		this._prev = 0.0;
	}

	onChangeTime(e){
		super.onChangeTime(e);
		this.outs[this.Out.diff].latch = (this.ins[this.In.in_].val - this._prev)/e.dt;
		this._prev = this.ins[this.In.in_].val;
		return this.update();
	}
};

var Buffer = class extends Component{
	get In(){ return {in_: 0}; }
	get Out(){ return {delay: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
	}

	onChangeTime(e){
		super.onChangeTime(e);
		this.outs[this.Out.delay].latch = this.ins[this.In.in_].val;;
		return this.update();
	}
};

var Sine = class extends Component{
	get In(){ return {freq: 0, }; }
	get Out(){ return {sine: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);

		this.phase = 0.0;
	}

	onChangeTime(e){
		super.onChangeTime(e);
		this.outs[this.Out.sine].latch = Math.sin(this.phase);
		this.phase += this.ins[this.In.freq].val*e.dt*2.0*Math.PI;
		return this.update();
	}
};

var Square = class extends Component{
	get In(){ return {freq: 0, duty: 1}; }
	get Out(){ return {square: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);

		this.phase = 0.0;
	}

	onChangeTime(e){
		super.onChangeTime(e);
		this.outs[this.Out.square].latch = (this.phase%(2.0*Math.PI)<2.0*Math.PI*this.ins[this.In.duty].val)?1.0:-1.0;
		this.phase += this.ins[this.In.freq].val*e.dt*2.0*Math.PI;
		return this.update();
	}
};

var Saw = class extends Component{
	get In(){ return {freq: 0, }; }
	get Out(){ return {saw: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
		this.phase = 0.0;
	}

	onChangeTime(e){
		super.onChangeTime(e);
		this.outs[this.Out.saw].latch = this.phase%(2.0*Math.PI)/(2.0*Math.PI)*2.0 - 1.0;
		this.phase += this.ins[this.In.freq].val*e.dt*2.0*Math.PI;
		return this.update();
	}
};

var Triangle = class extends Component{
	get In(){ return {freq: 0, }; }
	get Out(){ return {tri: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
		this.phase = 0.0;
	}

	onChangeTime(e){
		super.onChangeTime(e);
		var o_phase = this.phase%(2.0*Math.PI);
		if (o_phase<Math.PI){
			this.outs[this.Out.tri].latch = o_phase/Math.PI*2.0 - 1.0;
		}else{
			this.outs[this.Out.tri].latch = (1.0 - (o_phase - Math.PI)/Math.PI)*2.0 - 1.0;
		}
		this.phase += this.ins[this.In.freq].val*e.dt*2.0*Math.PI;
		return this.update();
	}
};

var Noise = class extends Component{
	get In(){ return {}; }
	get Out(){ return {noise: 0, }; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
	}

	onChangeTime(e){
		super.onChangeTime(e);
		this.outs[this.Out.noise].latch = Math.random()*2.0 - 1.0;
		return this.update();
	}
};

var Scope = class extends Component{
	get In(){ return {in_: 0}; }
	get Out(){ return {thru: 0, }; }
	static get Mode(){ return {freerun: 0, rising: 1, falling: 2}; }

	constructor(){
		super();
		this.initPort(Object.keys(this.In).length, Object.keys(this.Out).length);
		this.ui_class = UiScope;

		this.mode = Scope.Mode.freerun;
		this.data = [];
		this.trig = 0.0;
		this._skip = 1;
		this._prev = 0.0;
		this._data_i = 0;
		this._data_j = 0;
		for(var i = 0; i<256; i++) this.data.push(0.0);
	}

	onChangeIn(){
		super.onChangeIn();
		this.outs[this.Out.thru].latch = this.ins[this.In.in_].val;
		return this.update();
	}

	onChangeTime(e){
		super.onChangeTime(e);
		if (this._data_j%this._skip==0){
			var go;
			if (this._data_i){
				go = true;
			}else{
				switch(this.mode){
					case Scope.Mode.freerun:
					go = true;
					break;
					case Scope.Mode.rising:
					go = this.prev<this.trig && this.trig<=this.ins[this.In.in_].val;
					break;
					case Scope.Mode.falling:
					go = this.ins[this.In.in_].val<this.trig && this.trig<=this.prev;
					break;
				}
			}

			if (go){
				this.data[this._data_i++] = this.ins[this.In.in_].val;
				this._data_i %= 256;
			}
			this.prev = this.ins[this.In.in_].val;
		}
		this._data_j++;
	}

	set skip(value){
		if (value>0) this._skip = value;
	}
};

var UiScope = class extends UiComponent{
	constructor(com, uisk){
		super(com, uisk);
		this._canvas = document.createElement("Canvas");

		var labels = [];
		for(var i = 0; i<256; i++) labels.push(i);

var ctx = this._canvas.getContext('2d');
		this._chart = new Chart(ctx, {
    type: 'line',
    data: {
	labels: labels,
        datasets: [{
		data: this.com.data,
		fill: false,
		borderColor: "gray",
		borderWidth: 1,
		lineTension: 0,
		pointRadius: 0,
        }]
    },
    options: {
	responsive: false,
	legend: { display: false },
	animation:false,
	scales: {
		xAxes: [{
			display: false,
		}],
	},
    }
});

		this._jqmodebtn = $('<button>Free Run</button>');
		this._jqtrig = $('<input value="0.0" style="width: 48px; ">');
		this._jqskip = $('<input type="number" value="1" style="width: 48px; " min="1">');
	}

	initDom(){
		super.initDom();
		var column = $('<td colspan="2" style="text-align: center; "></td>');
		column.append(this._jqmodebtn);
		column.append(" Trigger ");
		column.append(this._jqtrig);
		column.append(" Skip ");
		column.append(this._jqskip);
		column.append(this._canvas);
		this.jqobj.append($('<tr></tr>').append(column));

		this._timer = setInterval(() => {
			this._chart.update();
		}, 16);
		this._jqmodebtn.click((e) => {
			switch(this.mode){
				case Scope.Mode.freerun:
				this.mode = Scope.Mode.rising;
				break;
				case Scope.Mode.rising:
				this.mode = Scope.Mode.falling;
				break;
				case Scope.Mode.falling:
				this.mode = Scope.Mode.freerun;
				break;
			}
		});
		this._jqtrig.change((e) => {
			this.trig = this.trig;
		});
		this._jqskip	.change((e) => {
			this.skip = this.skip;
		});
	}

	get mode(){
		return this.com.mode;
	}
	set mode(value){
		this.com.mode = value;
		switch(value){
			case Scope.Mode.freerun:
			this._jqmodebtn.text("Free Run");
			break;
			case Scope.Mode.rising:
			this._jqmodebtn.text("Rising Edge");
			break;
			case Scope.Mode.falling:
			this._jqmodebtn.text("Falling Edge");
			break;
		}
	}

	get trig(){
		return Number(this._jqtrig.val());
	}
	set trig(value){
		this.com.trig = value;
		this._jqtrig.val(value);
	}

	get skip(){
		return Number(this._jqskip.val());
	}
	set skip(value){
		this.com.skip = value;
		this._jqskip.val(value);
	}

	export(){
		var ex = super.export();
		ex.mode = this.mode;
		ex.trig = this.trig;
		ex.skip = this.skip;
		return ex;
	}

	import(im){
		super.import(im);
		this.mode = im.mode;
		this.trig = im.trig;
		this.skip = im.skip;
	}

	dispose(){
		clearInterval(this._timer);
		super.dispose();
	}
};
