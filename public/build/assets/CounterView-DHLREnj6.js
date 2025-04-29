import{_ as E}from"./AuthenticatedLayout-D2iCQmtW.js";import{B as A,R as D,q,K as I,L as Q,I as R,c as m,o as p,e as i,E as _,n as F,j as v,p as w,t as h,M as B,N as x,D as H,F as W,O as M,k as $,P as U,b as f,d as G,r as T,a as y,u as g,s as O,Q as Y,l as P,W as L}from"./app-BWNrNjCQ.js";import{u as J}from"./index-D6OEKSfl.js";import{s as X}from"./index-wXlZKAty.js";import{a as Z,s as S}from"./index-DQDpI-0R.js";import{d as z}from"./index-C-9JGpXp.js";import"./ApplicationLogo-BY06AJv4.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./index-a8w4ol-7.js";import"./index-C45ElIFY.js";import"./index-D-cxfG7v.js";import"./index-DmKeCkvW.js";var ee=({dt:e})=>`
.p-togglebutton {
    display: inline-flex;
    cursor: pointer;
    user-select: none;
    overflow: hidden;
    position: relative;
    color: ${e("togglebutton.color")};
    background: ${e("togglebutton.background")};
    border: 1px solid ${e("togglebutton.border.color")};
    padding: ${e("togglebutton.padding")};
    font-size: 1rem;
    font-family: inherit;
    font-feature-settings: inherit;
    transition: background ${e("togglebutton.transition.duration")}, color ${e("togglebutton.transition.duration")}, border-color ${e("togglebutton.transition.duration")},
        outline-color ${e("togglebutton.transition.duration")}, box-shadow ${e("togglebutton.transition.duration")};
    border-radius: ${e("togglebutton.border.radius")};
    outline-color: transparent;
    font-weight: ${e("togglebutton.font.weight")};
}

.p-togglebutton-content {
    display: inline-flex;
    flex: 1 1 auto;
    align-items: center;
    justify-content: center;
    gap: ${e("togglebutton.gap")};
    padding: ${e("togglebutton.content.padding")};
    background: transparent;
    border-radius: ${e("togglebutton.content.border.radius")};
    transition: background ${e("togglebutton.transition.duration")}, color ${e("togglebutton.transition.duration")}, border-color ${e("togglebutton.transition.duration")},
            outline-color ${e("togglebutton.transition.duration")}, box-shadow ${e("togglebutton.transition.duration")};
}

.p-togglebutton:not(:disabled):not(.p-togglebutton-checked):hover {
    background: ${e("togglebutton.hover.background")};
    color: ${e("togglebutton.hover.color")};
}

.p-togglebutton.p-togglebutton-checked {
    background: ${e("togglebutton.checked.background")};
    border-color: ${e("togglebutton.checked.border.color")};
    color: ${e("togglebutton.checked.color")};
}

.p-togglebutton-checked .p-togglebutton-content {
    background: ${e("togglebutton.content.checked.background")};
    box-shadow: ${e("togglebutton.content.checked.shadow")};
}

.p-togglebutton:focus-visible {
    box-shadow: ${e("togglebutton.focus.ring.shadow")};
    outline: ${e("togglebutton.focus.ring.width")} ${e("togglebutton.focus.ring.style")} ${e("togglebutton.focus.ring.color")};
    outline-offset: ${e("togglebutton.focus.ring.offset")};
}

.p-togglebutton.p-invalid {
    border-color: ${e("togglebutton.invalid.border.color")};
}

.p-togglebutton:disabled {
    opacity: 1;
    cursor: default;
    background: ${e("togglebutton.disabled.background")};
    border-color: ${e("togglebutton.disabled.border.color")};
    color: ${e("togglebutton.disabled.color")};
}

.p-togglebutton-label,
.p-togglebutton-icon {
    position: relative;
    transition: none;
}

.p-togglebutton-icon {
    color: ${e("togglebutton.icon.color")};
}

.p-togglebutton:not(:disabled):not(.p-togglebutton-checked):hover .p-togglebutton-icon {
    color: ${e("togglebutton.icon.hover.color")};
}

.p-togglebutton.p-togglebutton-checked .p-togglebutton-icon {
    color: ${e("togglebutton.icon.checked.color")};
}

.p-togglebutton:disabled .p-togglebutton-icon {
    color: ${e("togglebutton.icon.disabled.color")};
}

.p-togglebutton-sm {
    padding: ${e("togglebutton.sm.padding")};
    font-size: ${e("togglebutton.sm.font.size")};
}

.p-togglebutton-sm .p-togglebutton-content {
    padding: ${e("togglebutton.content.sm.padding")};
}

.p-togglebutton-lg {
    padding: ${e("togglebutton.lg.padding")};
    font-size: ${e("togglebutton.lg.font.size")};
}

.p-togglebutton-lg .p-togglebutton-content {
    padding: ${e("togglebutton.content.lg.padding")};
}
`,te={root:function(t){var n=t.instance,r=t.props;return["p-togglebutton p-component",{"p-togglebutton-checked":n.active,"p-invalid":n.$invalid,"p-togglebutton-sm p-inputfield-sm":r.size==="small","p-togglebutton-lg p-inputfield-lg":r.size==="large"}]},content:"p-togglebutton-content",icon:"p-togglebutton-icon",label:"p-togglebutton-label"},ne=A.extend({name:"togglebutton",style:ee,classes:te}),oe={name:"BaseToggleButton",extends:z,props:{onIcon:String,offIcon:String,onLabel:{type:String,default:"Yes"},offLabel:{type:String,default:"No"},iconPos:{type:String,default:"left"},readonly:{type:Boolean,default:!1},tabindex:{type:Number,default:null},ariaLabelledby:{type:String,default:null},ariaLabel:{type:String,default:null},size:{type:String,default:null}},style:ne,provide:function(){return{$pcToggleButton:this,$parentInstance:this}}};function k(e){"@babel/helpers - typeof";return k=typeof Symbol=="function"&&typeof Symbol.iterator=="symbol"?function(t){return typeof t}:function(t){return t&&typeof Symbol=="function"&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},k(e)}function le(e,t,n){return(t=ae(t))in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function ae(e){var t=re(e,"string");return k(t)=="symbol"?t:t+""}function re(e,t){if(k(e)!="object"||!e)return e;var n=e[Symbol.toPrimitive];if(n!==void 0){var r=n.call(e,t);if(k(r)!="object")return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return(t==="string"?String:Number)(e)}var K={name:"ToggleButton",extends:oe,inheritAttrs:!1,emits:["change"],methods:{getPTOptions:function(t){var n=t==="root"?this.ptmi:this.ptm;return n(t,{context:{active:this.active,disabled:this.disabled}})},onChange:function(t){!this.disabled&&!this.readonly&&(this.writeValue(!this.d_value,t),this.$emit("change",t))},onBlur:function(t){var n,r;(n=(r=this.formField).onBlur)===null||n===void 0||n.call(r,t)}},computed:{active:function(){return this.d_value===!0},hasLabel:function(){return I(this.onLabel)&&I(this.offLabel)},label:function(){return this.hasLabel?this.d_value?this.onLabel:this.offLabel:" "},dataP:function(){return q(le({checked:this.active,invalid:this.$invalid},this.size,this.size))}},directives:{ripple:D}},ie=["tabindex","disabled","aria-pressed","aria-label","aria-labelledby","data-p-checked","data-p-disabled","data-p"],se=["data-p"];function ue(e,t,n,r,c,o){var u=Q("ripple");return R((p(),m("button",w({type:"button",class:e.cx("root"),tabindex:e.tabindex,disabled:e.disabled,"aria-pressed":e.d_value,onClick:t[0]||(t[0]=function(){return o.onChange&&o.onChange.apply(o,arguments)}),onBlur:t[1]||(t[1]=function(){return o.onBlur&&o.onBlur.apply(o,arguments)})},o.getPTOptions("root"),{"aria-label":e.ariaLabel,"aria-labelledby":e.ariaLabelledby,"data-p-checked":o.active,"data-p-disabled":e.disabled,"data-p":o.dataP}),[i("span",w({class:e.cx("content")},o.getPTOptions("content"),{"data-p":o.dataP}),[_(e.$slots,"default",{},function(){return[_(e.$slots,"icon",{value:e.d_value,class:F(e.cx("icon"))},function(){return[e.onIcon||e.offIcon?(p(),m("span",w({key:0,class:[e.cx("icon"),e.d_value?e.onIcon:e.offIcon]},o.getPTOptions("icon")),null,16)):v("",!0)]}),i("span",w({class:e.cx("label")},o.getPTOptions("label")),h(o.label),17)]})],16,se)],16,ie)),[[u]])}K.render=ue;var de=({dt:e})=>`
.p-selectbutton {
    display: inline-flex;
    user-select: none;
    vertical-align: bottom;
    outline-color: transparent;
    border-radius: ${e("selectbutton.border.radius")};
}

.p-selectbutton .p-togglebutton {
    border-radius: 0;
    border-width: 1px 1px 1px 0;
}

.p-selectbutton .p-togglebutton:focus-visible {
    position: relative;
    z-index: 1;
}

.p-selectbutton .p-togglebutton:first-child {
    border-inline-start-width: 1px;
    border-start-start-radius: ${e("selectbutton.border.radius")};
    border-end-start-radius: ${e("selectbutton.border.radius")};
}

.p-selectbutton .p-togglebutton:last-child {
    border-start-end-radius: ${e("selectbutton.border.radius")};
    border-end-end-radius: ${e("selectbutton.border.radius")};
}

.p-selectbutton.p-invalid {
    outline: 1px solid ${e("selectbutton.invalid.border.color")};
    outline-offset: 0;
}
`,ce={root:function(t){var n=t.instance;return["p-selectbutton p-component",{"p-invalid":n.$invalid}]}},ge=A.extend({name:"selectbutton",style:de,classes:ce}),be={name:"BaseSelectButton",extends:z,props:{options:Array,optionLabel:null,optionValue:null,optionDisabled:null,multiple:Boolean,allowEmpty:{type:Boolean,default:!0},dataKey:null,ariaLabelledby:{type:String,default:null},size:{type:String,default:null}},style:ge,provide:function(){return{$pcSelectButton:this,$parentInstance:this}}};function pe(e,t){var n=typeof Symbol<"u"&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=j(e))||t){n&&(e=n);var r=0,c=function(){};return{s:c,n:function(){return r>=e.length?{done:!0}:{done:!1,value:e[r++]}},e:function(l){throw l},f:c}}throw new TypeError(`Invalid attempt to iterate non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`)}var o,u=!0,a=!1;return{s:function(){n=n.call(e)},n:function(){var l=n.next();return u=l.done,l},e:function(l){a=!0,o=l},f:function(){try{u||n.return==null||n.return()}finally{if(a)throw o}}}}function fe(e){return me(e)||ye(e)||j(e)||he()}function he(){throw new TypeError(`Invalid attempt to spread non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`)}function j(e,t){if(e){if(typeof e=="string")return C(e,t);var n={}.toString.call(e).slice(8,-1);return n==="Object"&&e.constructor&&(n=e.constructor.name),n==="Map"||n==="Set"?Array.from(e):n==="Arguments"||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?C(e,t):void 0}}function ye(e){if(typeof Symbol<"u"&&e[Symbol.iterator]!=null||e["@@iterator"]!=null)return Array.from(e)}function me(e){if(Array.isArray(e))return C(e)}function C(e,t){(t==null||t>e.length)&&(t=e.length);for(var n=0,r=Array(t);n<t;n++)r[n]=e[n];return r}var N={name:"SelectButton",extends:be,inheritAttrs:!1,emits:["change"],methods:{getOptionLabel:function(t){return this.optionLabel?x(t,this.optionLabel):t},getOptionValue:function(t){return this.optionValue?x(t,this.optionValue):t},getOptionRenderKey:function(t){return this.dataKey?x(t,this.dataKey):this.getOptionLabel(t)},isOptionDisabled:function(t){return this.optionDisabled?x(t,this.optionDisabled):!1},isOptionReadonly:function(t){if(this.allowEmpty)return!1;var n=this.isSelected(t);return this.multiple?n&&this.d_value.length===1:n},onOptionSelect:function(t,n,r){var c=this;if(!(this.disabled||this.isOptionDisabled(n)||this.isOptionReadonly(n))){var o=this.isSelected(n),u=this.getOptionValue(n),a;if(this.multiple)if(o){if(a=this.d_value.filter(function(b){return!B(b,u,c.equalityKey)}),!this.allowEmpty&&a.length===0)return}else a=this.d_value?[].concat(fe(this.d_value),[u]):[u];else{if(o&&!this.allowEmpty)return;a=o?null:u}this.writeValue(a,t),this.$emit("change",{event:t,value:a})}},isSelected:function(t){var n=!1,r=this.getOptionValue(t);if(this.multiple){if(this.d_value){var c=pe(this.d_value),o;try{for(c.s();!(o=c.n()).done;){var u=o.value;if(B(u,r,this.equalityKey)){n=!0;break}}}catch(a){c.e(a)}finally{c.f()}}}else n=B(this.d_value,r,this.equalityKey);return n}},computed:{equalityKey:function(){return this.optionValue?null:this.dataKey},dataP:function(){return q({invalid:this.$invalid})}},directives:{ripple:D},components:{ToggleButton:K}},ve=["aria-labelledby","data-p"];function $e(e,t,n,r,c,o){var u=H("ToggleButton");return p(),m("div",w({class:e.cx("root"),role:"group","aria-labelledby":e.ariaLabelledby},e.ptmi("root"),{"data-p":o.dataP}),[(p(!0),m(W,null,M(e.options,function(a,b){return p(),$(u,{key:o.getOptionRenderKey(a),modelValue:o.isSelected(a),onLabel:o.getOptionLabel(a),offLabel:o.getOptionLabel(a),disabled:e.disabled||o.isOptionDisabled(a),unstyled:e.unstyled,size:e.size,readonly:o.isOptionReadonly(a),onChange:function(d){return o.onOptionSelect(d,a,b)},pt:e.ptm("pcToggleButton")},U({_:2},[e.$slots.option?{name:"default",fn:f(function(){return[_(e.$slots,"option",{option:a,index:b},function(){return[i("span",w({ref_for:!0},e.ptm("pcToggleButton").label),h(o.getOptionLabel(a)),17)]})]}),key:"0"}:void 0]),1032,["modelValue","onLabel","offLabel","disabled","unstyled","size","readonly","onChange","pt"])}),128))],16,ve)}N.render=$e;const we={class:"flex"},Se={class:"mr-auto"},ke={class:"text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"},xe={class:"ml-auto"},Oe={class:"mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8"},Le={class:"flex flex-col gap-6 xl:flex-row"},Be={class:"border-surface rounded-2xl bg-white px-7 py-5 xl:w-96 dark:bg-gray-800"},_e={key:0,class:"flex flex-col justify-between gap-4"},Ce={class:"mb-5 rounded-lg border border-gray-300 p-4"},Ve={class:"my-4 text-5xl font-bold text-blue-600"},Ie={class:"text-gray-500"},Te={class:"flex max-w-md justify-between gap-3"},Pe={key:0,class:"rounded-lg border border-blue-500 bg-blue-100 px-4 py-3 text-blue-700",role:"alert"},Ae={class:"text-sm"},De={class:"card border-surface flex-1 rounded-xl bg-white p-2"},qe={class:"flex flex-wrap items-center justify-between gap-2"},ze={key:0},Je=G({__name:"CounterView",props:{counter:{},calledQueue:{},queues:{},statuses:{},status:{}},setup(e){const t=T(typeof e.calledQueue=="object"&&e.calledQueue!==null&&"id"in e.calledQueue&&"number"in e.calledQueue?e.calledQueue:null),n=T(e.status),r=l=>{var s;const d=l.value;(s=e.counter)!=null&&s.id?L.reload({only:["queues"],data:{status:d}}):console.error("Counter ID is undefined or null.")},c=()=>{var l;L.get(`/queue/call/${(l=e.counter)==null?void 0:l.id}`,{preserveScroll:!1})},o=()=>{var l;L.post(`/queue/${(l=t.value)==null?void 0:l.id}/finish`,{},{onSuccess:()=>{t.value&&(t.value.status="done")}})},u=async()=>{t.value&&(console.log(t.value.id),await a(t.value.id))};J(async()=>{u()},3e3);async function a(l){try{const s=await(await fetch(`/queue/${l}`)).json();return console.log(s),t.value=s,s||null}catch(d){return console.error("Gagal fetch queue:",d),null}}const b=l=>{switch(l){case"waiting":return"warn";case"calling":return"success";case"done":return"contrast";case"skipped":return"danger";default:return}};return(l,d)=>(p(),$(E,null,{header:f(()=>{var s,V;return[i("div",we,[i("div",Se,[i("h2",ke,h((s=l.counter)==null?void 0:s.name),1),i("div",null,h((V=l.counter)==null?void 0:V.service.name),1)]),i("div",xe,[y(g(O),{rounded:"",outlined:"",icon:"pi pi-times",class:"p-button-danger",onClick:d[0]||(d[0]=Ke=>g(L).get("/counter"))})])])]}),default:f(()=>[i("div",Oe,[i("div",Le,[i("div",Be,[d[2]||(d[2]=i("div",{class:"mb-6 flex items-center gap-6"},[i("div",{class:"text-color flex-1 font-semibold leading-6"}," Sedang Dipanggil : ")],-1)),t.value?(p(),m("div",_e,[i("div",null,[i("div",Ce,[i("h2",Ve,h(t.value.number),1),i("div",Ie,h(t.value.called_at||"calling..."),1)]),i("div",Te,[t.value.status!="done"?(p(),$(g(O),{key:0,label:"Done",class:"p-button-contrast mr-2",onClick:o,disabled:!(t.value.status=="done"||t.value.called_at)},null,8,["disabled"])):v("",!0),y(g(O),{label:"Next",class:"p-button-primary",onClick:c})])])])):(p(),$(g(X),{key:1},{content:f(()=>[l.$page.props.flash.message?(p(),m("div",Pe,[i("p",Ae,h(l.$page.props.flash.message),1)])):v("",!0),y(g(O),{label:"Call",class:"p-button-primary mt-4 w-full",onClick:c})]),_:1}))]),i("div",De,[y(g(Z),{value:l.queues,scrollable:"",scrollHeight:"400px",tableStyle:"min-width: 30rem",size:"small",class:"w-full text-sm"},{header:f(()=>[i("div",qe,[d[3]||(d[3]=i("span",{class:"text-xl font-bold"},"Queues",-1)),y(g(N),{options:l.statuses,modelValue:n.value,"onUpdate:modelValue":d[1]||(d[1]=s=>n.value=s),"option-value":"value","option-label":"label",size:"small",onChange:r},null,8,["options","modelValue"])])]),footer:f(()=>[P(" In total there are "+h(l.queues?l.queues.length:0)+" queues. ",1)]),default:f(()=>[y(g(S),{field:"number",header:"Number","body-class":"font-bold font-mono"}),n.value!="waiting"?(p(),$(g(S),{key:0,field:"counter.name",header:"Counter"})):v("",!0),y(g(S),{header:"Status"},{body:f(s=>[y(g(Y),{value:s.data.status,severity:b(s.data.status)},null,8,["value","severity"])]),_:1}),y(g(S),{field:"created_at",header:"Created At","header-class":"w-40 text-sm text-right"},{body:f(s=>[P(h(new Date(s.data.created_at).toLocaleString("id-ID",{day:"numeric",month:"short",year:"numeric",hour:"2-digit",minute:"2-digit",second:"2-digit",hour12:!1})),1)]),_:1}),n.value=="calling"?(p(),$(g(S),{key:1,field:"called_at",header:"Called At","header-class":"w-40 text-sm text-right"},{body:f(s=>[s.data.called_at?(p(),m("div",ze,h(new Date(s.data.called_at).toLocaleString("id-ID",{day:"numeric",month:"short",year:"numeric",hour:"2-digit",minute:"2-digit",second:"2-digit",hour12:!1})),1)):v("",!0)]),_:1})):v("",!0)]),_:1},8,["value"])])])])]),_:1}))}});export{Je as default};
