"use strict";(self.webpackChunkquickloans=self.webpackChunkquickloans||[]).push([[571],{4571:function(e,n,t){t.r(n);var r=t(1413),i=t(4165),a=t(5861),l=t(9439),s=t(697),o=t(890),c=t(9164),d=t(1889),u=t(8096),x=t(4925),m=t(7198),p=t(3786),h=t(7071),v=t(8550),f=t(3400),Z=t(4294),g=t(9281),j=t(9836),b=t(6890),y=t(5855),w=t(3994),k=t(3382),T=t(2791),S=t(9085),z=t(2575),C=t(383),A=t(2506),B=t(9596),I=t(5130),N=t(6727),E=t(4810),D=t(6109),F=t(706),H=t(2550),M=t(453),W=t(184);n.default=function(){var e=(0,T.useState)([]),n=(0,l.Z)(e,2),t=n[0],q=n[1],L=(0,T.useState)([]),P=(0,l.Z)(L,2),Q=P[0],R=P[1],O=(0,T.useState)(""),U=((0,l.Z)(O,1)[0],(0,T.useState)("")),V=(0,l.Z)(U,2),_=V[0],J=V[1],Y={loanType:"",text:"",image:"",id:""},G=(0,T.useState)(Y),K=(0,l.Z)(G,2),X=K[0],$=K[1],ee=(0,T.useState)({open:!1,data:null}),ne=(0,l.Z)(ee,2),te=ne[0],re=ne[1],ie=N.Ry().shape({loanType:N.Z_().required("Loan type is required")}),ae=function(){var e=(0,a.Z)((0,i.Z)().mark((function e(){var n,t,r,a,l;return(0,i.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=(0,D.gQ)(),t=n.signal,e.next=3,(0,M.Eg)(t);case 3:r=e.sent,a=r.error,l=r.data,a||q(l);case 7:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}(),le=function(){var e=(0,a.Z)((0,i.Z)().mark((function e(){var n,t,r,a,l;return(0,i.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=(0,D.gQ)(),t=n.signal,e.next=3,(0,F.fv)(t);case 3:r=e.sent,a=r.error,l=r.data,a||R(l);case 7:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}(),se=function(){var e=(0,a.Z)((0,i.Z)().mark((function e(n){var t;return(0,i.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t={id:null===n||void 0===n?void 0:n.id,loanType:null===n||void 0===n?void 0:n.loanTypeID,text:null===n||void 0===n?void 0:n.text},$(t),J(n.image);case 3:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}(),oe=function(){var e=(0,a.Z)((0,i.Z)().mark((function e(n){var t,a,l,s;return(0,i.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t=(0,D.gQ)(),a=t.signal,e.next=3,(0,M.H_)(n,a);case 3:l=e.sent,s=l.data,l.error?S.Am.error(s.message):(S.Am.success(s.message),ae()),re((0,r.Z)((0,r.Z)({},te),{},{open:!1}));case 8:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}(),ce=function(){var e=(0,a.Z)((0,i.Z)().mark((function e(n){var t,r,a,l,s,o;return(0,i.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(r=(0,D.gQ)(),a=r.signal,null!==n&&void 0!==n&&n.id||delete n.id,null!==n&&void 0!==n&&null!==(t=n.image)&&void 0!==t&&t.name){e.next=5;break}return S.Am.warning("Banner image is required"),e.abrupt("return");case 5:return l=new FormData,Object.keys(n).forEach((function(e){return n[e]&&l.append(e,n[e])})),e.next=9,(0,M.zm)(l,a);case 9:if(s=e.sent,o=s.data,s.error){e.next=17;break}return S.Am.success(o.message),e.abrupt("return",!0);case 17:return S.Am.error(o.message),e.abrupt("return",!1);case 19:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}();return(0,T.useEffect)((function(){ae(),le()}),[]),(0,W.jsxs)("div",{children:[(0,W.jsx)(s.Z,{sx:{bgcolor:"background.paper",mx:2,p:2},children:(0,W.jsx)(o.Z,{component:"div",sx:{fontSize:30,fontWeight:600},children:"Home Banner"})}),(0,W.jsxs)(s.Z,{sx:{bgcolor:"background.paper",display:"flex",flexDirection:"column",mx:2,p:2,mt:2},children:[(0,W.jsx)(o.Z,{component:"div",sx:{fontSize:25,fontWeight:600},children:"Add/Update Home Banner"}),(0,W.jsx)(c.Z,{maxWidth:"xs",children:(0,W.jsx)(A.J9,{initialValues:X,validationSchema:ie,enableReinitialize:!0,onSubmit:function(){var e=(0,a.Z)((0,i.Z)().mark((function e(n,t){var r;return(0,i.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.setSubmitting,r=t.resetForm,e.next=3,ce(n);case 3:e.sent&&($(Y),r(),ae());case 5:case"end":return e.stop()}}),e)})));return function(n,t){return e.apply(this,arguments)}}(),children:function(e){var n,t,i=e.handleSubmit,a=e.handleChange,l=e.setFieldValue,s=e.values,c=e.touched,g=e.errors,j=e.resetForm;return(0,W.jsx)("form",{onSubmit:i,children:(0,W.jsxs)(d.ZP,{container:!0,spacing:4,children:[(0,W.jsx)(d.ZP,{item:!0,xl:12,lg:12,md:12,sm:12,xs:12,children:(0,W.jsxs)(u.Z,{fullWidth:!0,variant:"standard",children:[(0,W.jsx)(x.Z,{id:"demo-simple-select-standard-label",sx:{ml:2},children:"Select Loan Type"}),(0,W.jsxs)(m.Z,{labelId:"demo-simple-select-standard-label",name:"loanType",value:null===s||void 0===s?void 0:s.loanType,onChange:a,label:"Loan Type",variant:"filled",size:"small",sx:{pb:1},children:[(0,W.jsx)(p.Z,{value:"",children:(0,W.jsx)("em",{children:"None"})}),Q.length>0&&Q.map((function(e){return(0,W.jsx)(p.Z,{value:e.value,children:null===e||void 0===e?void 0:e.name},e.value)}))]}),(0,W.jsx)(h.Z,{className:null!==c&&void 0!==c&&c.loanType&&null!==g&&void 0!==g&&g.loanType?"text-danger":"",children:(null===c||void 0===c?void 0:c.loanType)&&(0,W.jsx)(W.Fragment,{children:null===g||void 0===g?void 0:g.loanType})})]})}),(0,W.jsx)(d.ZP,{item:!0,xl:12,lg:12,md:12,sm:12,xs:12,children:(0,W.jsx)(v.Z,{name:"text",value:null===s||void 0===s?void 0:s.text,onChange:a,fullWidth:!0,id:"filled-basic",label:"Banner Text",variant:"filled",error:c.text&&Boolean(g.text),helperText:c.text&&(0,W.jsx)(W.Fragment,{children:g.text})})}),(0,W.jsxs)(d.ZP,{item:!0,xl:12,lg:12,md:12,sm:12,xs:12,children:[(0,W.jsxs)(o.Z,{sx:{display:"flex"},children:[(0,W.jsxs)(f.Z,{component:"label",children:[(0,W.jsx)(B.Z,{sx:{fontSize:30}}),(0,W.jsx)("input",{type:"file",id:"bannerIcon",hidden:!0,onChange:function(e){return function(e,n){var t=e.target.files[0],r=document.getElementById("bannerIcon");t&&("image/webp"===t.type?n("image",t):(S.Am.warning("Please upload image with webp extesion"),r.value=""))}(e,l)},name:"image"})]}),(0,W.jsx)(o.Z,{sx:{mt:1.5},component:"span",children:s.id?"Replace existing file":"Select file to upload"})]}),(0,W.jsxs)("ul",{style:{marginTop:10,listStyle:"none"},id:"imagesSection",children:[s.id&&(0,W.jsxs)("li",{children:[(0,W.jsx)(E.Ee,{src:_,style:{width:"20%",marginBottom:20}})," "]}),(null===s||void 0===s||null===(n=s.image)||void 0===n?void 0:n.name)&&(0,W.jsxs)("li",{style:{display:"flex",alignItems:"flex-start"},children:[null===s||void 0===s||null===(t=s.image)||void 0===t?void 0:t.name," ",(0,W.jsx)(I.Z,{onClick:function(){return l("image",void 0)},sx:{color:"black",cursor:"pointer"}})," "]})]})]}),(0,W.jsxs)(o.Z,{component:"div",sx:{textAlign:"center",width:"100%"},children:[(0,W.jsx)(Z.Z,{sx:(0,r.Z)((0,r.Z)({},H.x1.button),{},{mt:1,":hover":(0,r.Z)({},H.x1.button)}),variant:"contained",size:"large",type:"submit",children:"Submit"})," ","\xa0\xa0",(0,W.jsx)(Z.Z,{sx:(0,r.Z)((0,r.Z)({},H.x1.outlinedBtn),{},{mt:1}),variant:"outlined",size:"large",onClick:function(){$(Y),j()},children:"Reset"})]})]})})}})})]}),(0,W.jsx)(s.Z,{sx:{bgcolor:"background.paper",display:"flex",flexDirection:"column",mx:2,p:2,mt:2},children:(0,W.jsx)(g.Z,{children:(0,W.jsxs)(j.Z,{sx:{minWidth:950},"aria-label":"simple table",children:[(0,W.jsx)(b.Z,{children:(0,W.jsx)(y.Z,{children:["Banner Image","Loan Type","Banner Text","Actions"].map((function(e){return(0,W.jsx)(w.Z,{className:"text-center",children:e},e)}))})}),(0,W.jsx)(k.Z,{children:(null===t||void 0===t?void 0:t.length)>0&&(null===t||void 0===t?void 0:t.map((function(e){return(0,W.jsxs)(y.Z,{sx:{"&:last-child td, &:last-child th":{border:0}},children:[(0,W.jsx)(w.Z,{className:"text-center",sx:{width:"".concat(25,"%")},children:(0,W.jsx)(E.Ee,{src:e.image,style:{width:"50%"}})}),(0,W.jsx)(w.Z,{sx:{width:"".concat(25,"%")},className:"text-center",children:null===e||void 0===e?void 0:e.loanType}),(0,W.jsx)(w.Z,{className:"text-center",sx:{width:"".concat(25,"%")},children:(null===e||void 0===e?void 0:e.text)||"N/A"}),(0,W.jsxs)(w.Z,{className:"text-center",sx:{width:"".concat(25,"%")},children:[(0,W.jsx)(Z.Z,{variant:"outlined",size:"small",startIcon:(0,W.jsx)(z.Z,{}),onClick:function(){return se(e)},children:"Edit"})," ","\xa0",(0,W.jsx)(Z.Z,{variant:"outlined",size:"small",startIcon:(0,W.jsx)(C.Z,{}),onClick:function(){return re({open:!0,data:e.id})},children:"Delete"})]})]},e.id)})))})]})})}),(0,W.jsx)(E.N4,{open:te.open,toggleModal:function(){return re((0,r.Z)((0,r.Z)({},te),{},{open:!1}))},modalType:"warning",title:"Delete",content:"Are you sure you want to delete?",okText:"Yes",onOk:function(){return oe(te.data)}})]})}},2575:function(e,n,t){var r=t(4836);n.Z=void 0;var i=r(t(5649)),a=t(184),l=(0,i.default)((0,a.jsx)("path",{d:"M22 24H2v-4h20v4zM13.06 5.19l3.75 3.75L7.75 18H4v-3.75l9.06-9.06zm4.82 2.68-3.75-3.75 1.83-1.83c.39-.39 1.02-.39 1.41 0l2.34 2.34c.39.39.39 1.02 0 1.41l-1.83 1.83z"}),"BorderColor");n.Z=l},5130:function(e,n,t){var r=t(4836);n.Z=void 0;var i=r(t(5649)),a=t(184),l=(0,i.default)((0,a.jsx)("path",{d:"M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"}),"Clear");n.Z=l},9596:function(e,n,t){var r=t(4836);n.Z=void 0;var i=r(t(5649)),a=t(184),l=(0,i.default)((0,a.jsx)("path",{d:"M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"}),"CloudUpload");n.Z=l}}]);
//# sourceMappingURL=571.ce42f659.chunk.js.map