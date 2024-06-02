"use strict";(self.webpackChunkquickloans=self.webpackChunkquickloans||[]).push([[327],{7899:function(e,n,r){r.d(n,{tP:function(){return c},yT:function(){return b},nm:function(){return Z},oJ:function(){return _}});r(5462);var t=r(9164),o=r(4810),i=r(1780),a=r(933),l=r(6109),s=r(2395),d=r(2506),u=r(184),c=function(e){var n,r,c,v,p,g=e.initValues,f=e.handleSelectionChange,m=(0,d.u6)(),h=m.values,x=m.touched,b=m.handleChange,Z=m.setFieldTouched,y=m.errors,w=(m.setFieldValue,(0,i.VJ)().state);return(0,u.jsxs)(t.Z,{maxWidth:"sm",className:"mw-50 py-3",children:[(0,u.jsx)(o.oi,{fullWidth:!0,name:"borrower.loanAmount",label:"Loan Amount",value:null===h||void 0===h||null===(n=h.borrower)||void 0===n?void 0:n.loanAmount,onChange:function(e){b(e),Z(e.target.name)},className:(0,l.ll)(null===x||void 0===x||null===(r=x.borrower)||void 0===r?void 0:r.loanAmount,null===y||void 0===y||null===(c=y.borrower)||void 0===c?void 0:c.loanAmount),helperText:(0,l.by)(null===x||void 0===x||null===(v=x.borrower)||void 0===v?void 0:v.loanAmount,null===y||void 0===y||null===(p=y.borrower)||void 0===p?void 0:p.loanAmount)}),(0,u.jsx)(s.Z,{label:"Loan Type",name:"loanType",value:null===h||void 0===h?void 0:h.loanType,disabled:null===g||void 0===g?void 0:g.loanType,options:null===w||void 0===w?void 0:w.loanTypes,className:(0,l.ll)(null===x||void 0===x?void 0:x.loanType,null===y||void 0===y?void 0:y.loanType),onChange:function(e){b(e),Z(e.target.name)},helperText:(0,l.by)(null===x||void 0===x?void 0:x.loanType,null===y||void 0===y?void 0:y.loanType)}),(0,u.jsx)(a.Iv,{children:(0,u.jsxs)(u.Fragment,{children:[(0,u.jsx)(o.kL,{defaultOpen:!0,type:"current",reference:"borrower",handleSelectionChange:f}),(0,u.jsx)(o.kL,{type:"permanent",reference:"borrower",handleSelectionChange:f})]})})]})},v=r(4165),p=r(5861),g=r(9439),f=r(2791),m=r(9085),h=r(5584),x=r(2550),b=function(e){var n,r,a,l,c=e.initValues,b=e.removeCosigner,Z=e.handleSelectionChange,y=e.verifyCosignerOTP,w=(0,d.u6)(),j=w.handleChange,C=w.errors,T=w.values,k=w.touched,I=w.setFieldTouched,S=w.setFieldValue,N=(0,i.VJ)().state,A=(0,f.useState)(!1),E=(0,g.Z)(A,2),O=E[0],F=E[1],V=(0,f.useState)(!1),L=(0,g.Z)(V,2),P=L[0],Y=L[1],_=(0,f.useState)(!1),B=(0,g.Z)(_,2),Q=B[0],R=B[1],W=(0,f.useState)({}),q=(0,g.Z)(W,2),D=q[0],J=q[1],M=function(){return R(!Q)},z=function(){var e=(0,p.Z)((0,v.Z)().mark((function e(n,r,t){return(0,v.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!r){e.next=8;break}return e.next=3,b(r,T.leadId);case 3:e.sent&&(T.cosigner.splice(n,1),F(!O)),M(),e.next=10;break;case 8:t.remove(n),M();case 10:case"end":return e.stop()}}),e)})));return function(n,r,t){return e.apply(this,arguments)}}();return(0,u.jsxs)(t.Z,{maxWidth:"sm",className:"mw-50 py-3",children:[(1===(null===T||void 0===T?void 0:T.loanType)||2===(null===T||void 0===T?void 0:T.loanType))&&(0,u.jsxs)("div",{className:"tipForCosigner text-secondary",children:[(0,u.jsxs)("div",{children:[(0,u.jsx)(h.Z,{})," ",(0,u.jsxs)("u",{children:[(0,u.jsx)("span",{style:{fontWeight:"bold"},children:"Tip for Cosigner"})," "]})]}),(0,u.jsxs)("div",{children:["Once you save the details ",(0,u.jsx)("br",{})," of cosigner some fields may ",(0,u.jsx)("br",{}),"freeze so kindly fill it properly"]})]}),(1===(null===T||void 0===T?void 0:T.loanType)||2===(null===T||void 0===T?void 0:T.loanType))&&(0,u.jsx)(d.F2,{name:"cosigner",render:function(e){return(0,u.jsxs)(u.Fragment,{children:[(0,u.jsxs)("div",{className:"d-flex align-items-center",children:[(0,u.jsx)(o.zx,{style:x.x1.button,sx:{mb:4},onClick:function(){T.cosigner.length<4?(e.push({}),setTimeout((function(){var e=document.getElementById("cosigner-".concat(T.cosigner.length));null===e||void 0===e||e.scrollIntoView({behavior:"smooth",block:"start"})}),300)):m.Am.warning("Only 4 cosigners are allowed")},children:"Add-Cosigner +"}),(1===(null===T||void 0===T?void 0:T.loanType)||2===(null===T||void 0===T?void 0:T.loanType))&&(0,u.jsx)("div",{className:"tipForCosignerMobile",children:(0,u.jsx)(o.u,{open:P,handleTooltipOpen:function(){return Y(!0)},handleTooltipClose:function(){return Y(!1)},title:(0,u.jsxs)("span",{children:["Once you save the details ",(0,u.jsx)("br",{})," of cosigner some fields may ",(0,u.jsx)("br",{}),"freeze so kindly fill it properly"]}),children:(0,u.jsx)(h.Z,{})})})]}),(null===T||void 0===T?void 0:T.cosigner)&&T.cosigner.length>0&&T.cosigner.map((function(n,r){return(0,u.jsx)(o.Tp,{id:"cosigner-".concat(r),number:r,initialValues:null===c||void 0===c?void 0:c.cosigner,cosigner:n,removeCosigner:function(){return function(e,n,r,t){n?(J({index:e,id:n,arrayHelpers:r,cosignerName:t}),R(!0)):r.remove(e)}(r,null===n||void 0===n?void 0:n.id,e,"".concat(null===n||void 0===n?void 0:n.firstName," ").concat(null===n||void 0===n?void 0:n.lastName))},handleChange:j,errors:C,values:T,touched:k,setFieldTouched:I,handleSelectionChange:Z,totalCosigner:T.cosigner.length,setFieldValue:S,verifyCosignerOTP:y},r)}))]})}}),1===(null===T||void 0===T?void 0:T.loanType)&&(0,u.jsx)(s.Z,{label:"Course Country",name:"borrower.courseCountryId",value:null===T||void 0===T||null===(n=T.borrower)||void 0===n?void 0:n.courseCountryId,options:null===N||void 0===N?void 0:N.courseCountry,onChange:j}),2===(null===T||void 0===T?void 0:T.loanType)&&(0,u.jsx)(s.Z,{label:"Course",name:"borrower.courseId",value:null===T||void 0===T||null===(r=T.borrower)||void 0===r?void 0:r.courseId,options:null===N||void 0===N?void 0:N.courses,onChange:j}),(3===(null===T||void 0===T?void 0:T.loanType)||4===(null===T||void 0===T?void 0:T.loanType))&&(0,u.jsxs)(u.Fragment,{children:[(0,u.jsx)(s.Z,{label:"Work Status",name:"borrower.employmentTypeId",value:null===T||void 0===T||null===(a=T.borrower)||void 0===a?void 0:a.employmentTypeId,options:null===N||void 0===N?void 0:N.employment,onChange:j}),(0,u.jsx)(o.oi,{fullWidth:!0,label:"Salary",name:"borrower.salary",value:null===T||void 0===T||null===(l=T.borrower)||void 0===l?void 0:l.salary,onChange:j})]}),(0,u.jsx)(o.N4,{open:Q,toggleModal:M,modalType:"warning",title:"Delete Cosigner",content:"Are you sure you want to remove this cosigner ".concat(null===D||void 0===D?void 0:D.cosignerName),okText:"Yes",onOk:function(){return z(null===D||void 0===D?void 0:D.index,null===D||void 0===D?void 0:D.id,null===D||void 0===D?void 0:D.arrayHelpers)}})]})},Z=function(e){var n,r,i,a,l,s=e.handleFile,c=e.docloading,v=(0,d.u6)().values;return(0,u.jsxs)(t.Z,{maxWidth:"md",className:"mw-50 py-3",children:[(0,u.jsx)(o.BB,{heading:"borrower",individualName:null===v||void 0===v||null===(n=v.borrower)||void 0===n?void 0:n.name,values:null===v||void 0===v||null===(r=v.borrower)||void 0===r?void 0:r.fileObject,handleFile:s,reference:"Borrower",loanType:null===v||void 0===v?void 0:v.loanType,referenceId:null===v||void 0===v||null===(i=v.borrower)||void 0===i?void 0:i.id,docloading:null===c||void 0===c?void 0:c["Borrower-".concat(null===v||void 0===v||null===(a=v.borrower)||void 0===a?void 0:a.id)]}),(null===v||void 0===v?void 0:v.cosigner)&&(null===v||void 0===v||null===(l=v.cosigner)||void 0===l?void 0:l.length)>0&&v.cosigner.map((function(e,n){return(0,u.jsx)(o.BB,{cosignerNum:n,heading:null!==e&&void 0!==e&&e.relationship?null===e||void 0===e?void 0:e.relationship:"cosigner",individualName:null===e||void 0===e?void 0:e.name,values:null===e||void 0===e?void 0:e.fileObject,handleFile:s,reference:"Cosigner",loanType:null===v||void 0===v?void 0:v.loanType,referenceId:null===e||void 0===e?void 0:e.id,docloading:null===c||void 0===c?void 0:c["Cosigner-".concat(null===e||void 0===e?void 0:e.id)]},null===e||void 0===e?void 0:e.individualId)}))]})},y=r(1413),w=r(4942),j=r(6934),C=r(6856),T=r(3285),k=r(6314),I=r(3875),S=r(4512),N=r(5825),A=r(142),E=r(4008),O=r(1395),F=r(9930),V=(0,j.ZP)(C.Z)((function(e){var n,r=e.theme;return n={},(0,w.Z)(n,"&.".concat(T.Z.alternativeLabel),{top:22}),(0,w.Z)(n,"&.".concat(T.Z.active),(0,w.Z)({},"& .".concat(T.Z.line),{backgroundImage:"linear-gradient( 136deg, rgb(44 244 129) 0%, rgb(124 219 127) 50%, rgb(125 143 98) 100%)"})),(0,w.Z)(n,"&.".concat(T.Z.completed),(0,w.Z)({},"& .".concat(T.Z.line),{backgroundImage:"linear-gradient( 136deg, rgb(44 244 129) 0%, rgb(124 219 127) 50%, rgb(125 143 98) 100%)"})),(0,w.Z)(n,"& .".concat(T.Z.line),{height:3,border:0,backgroundColor:"dark"===r.palette.mode?r.palette.grey[800]:"#eaeaf0",borderRadius:1}),n})),L=(0,j.ZP)("div")((function(e){var n=e.theme,r=e.ownerState;return(0,y.Z)((0,y.Z)({backgroundColor:"dark"===n.palette.mode?n.palette.grey[700]:"#ccc",zIndex:1,color:"#fff",width:60,height:60,display:"flex",borderRadius:"50%",justifyContent:"center",alignItems:"center"},r.active&&{backgroundImage:"linear-gradient( 136deg, rgb(44 244 129) 0%, rgb(124 219 127) 50%, rgb(125 143 98) 100%)",boxShadow:"0 4px 10px 0 rgba(0,0,0,.25)"}),r.completed&&{backgroundImage:"linear-gradient( 136deg, rgb(44 244 129) 0%, rgb(124 219 127) 50%, rgb(125 143 98) 100%)"})}));function P(e){var n=e.active,r=e.completed,t=e.className,o={1:(0,u.jsx)(F.Z,{}),2:(0,u.jsx)(O.Z,{}),3:(0,u.jsx)(E.Z,{}),4:(0,u.jsx)(A.Z,{})};return(0,u.jsx)(L,{ownerState:{completed:r,active:n},className:t,children:o[String(e.icon)]})}var Y=["Lead (Creation & Validation)","Document Verification","Credit Evaluation","Loan Clearance"],_=function(e){var n=e.values,r=null!==n&&void 0!==n&&n.stageId?null===n||void 0===n?void 0:n.stageId:1;return(0,u.jsxs)(u.Fragment,{children:[(0,u.jsx)(k.Z,{sx:{width:"100%"},spacing:4,children:(0,u.jsx)(I.Z,{alternativeLabel:!0,activeStep:r-1,connector:(0,u.jsx)(V,{}),children:Y.map((function(e){return(0,u.jsx)(S.Z,{children:(0,u.jsx)(N.Z,{StepIconComponent:P,children:e})},e)}))})}),(0,u.jsxs)("div",{className:"mt-5 shadow-sm border",style:{minHeight:"10rem"},children:[(0,u.jsxs)("div",{className:"h3 text-center mt-2 text-primary font-weight-bold",children:["Your Lead Id - ",null===n||void 0===n?void 0:n.leadId," is in Stage ",null===n||void 0===n?void 0:n.stageId," (",null===n||void 0===n?void 0:n.stageName,")"]}),(0,u.jsxs)("div",{className:"h5 d-flex justify-content-center align-items-center m-4 text-secondary",children:["Your application status is ",null===n||void 0===n?void 0:n.statusName,"."]}),(0,u.jsx)("div",{className:"h6 d-flex justify-content-center align-items-center text-secondary m-4",children:"Keep in touch with us to complete your loan application"})]})]})}},8327:function(e,n,r){r.r(n),r.d(n,{default:function(){return I}});var t=r(7762),o=r(4942),i=r(1413),a=r(4165),l=r(5861),s=r(9439),d=r(2161),u=r(4294),c=r(9164),v=r(697),p=r(5527),g=r(3875),f=r(4512),m=r(5825),h=r(2506),x=r(2791),b=r(7689),Z=r(9085),y=(r(5462),r(1780)),w=r(7899),j=r(6109),C=r(706),T=r(184),k=["","",""],I=function(){var e=(0,x.useState)([]),n=(0,s.Z)(e,2),r=(n[0],n[1],(0,x.useState)(0)),I=(0,s.Z)(r,2),S=I[0],N=I[1],A=(0,x.useState)({}),E=(0,s.Z)(A,2),O=E[0],F=E[1],V=(0,y.VJ)().dispatch,L=(0,x.useState)({}),P=(0,s.Z)(L,2),Y=P[0],_=P[1],B=(0,b.s0)(),Q=(0,x.useCallback)(function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n,r){var t,o,i,l,s,d;return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!n){e.next=10;break}return t=(0,j.gQ)(),o=t.signal,e.next=4,(0,C.hw)(n,o);case 4:if(i=e.sent,l=i.data,s=i.error,null===l||void 0===l||!l.abort){e.next=9;break}return e.abrupt("return");case 9:s||(d=l.filter((function(e){return e.value===r})),V("PUSH_CITY",d[0]));case 10:case"end":return e.stop()}}),e)})));return function(n,r){return e.apply(this,arguments)}}(),[]),R=(0,x.useCallback)((function(e){null!==e&&void 0!==e&&e.currentCityId&&(null===e||void 0===e?void 0:e.currentCityId)===(null===e||void 0===e?void 0:e.permanentCityId)?Q(null===e||void 0===e?void 0:e.currentStateId,null===e||void 0===e?void 0:e.currentCityId):(Q(null===e||void 0===e?void 0:e.currentStateId,null===e||void 0===e?void 0:e.currentCityId),Q(null===e||void 0===e?void 0:e.permanentStateId,null===e||void 0===e?void 0:e.permanentCityId))}),[]);(0,x.useEffect)((function(){var e=(0,j.gQ)(),n=e.controller,r=e.signal;return(0,l.Z)((0,a.Z)().mark((function e(){var n,t,o,i,l,s,d,u,c,v,p,g;return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,(0,C.$D)(r);case 2:if(n=e.sent,t=n.data,o=n.error,null===t||void 0===t||!t.abort){e.next=7;break}return e.abrupt("return");case 7:if(o)Z.Am.error(t.message);else if(F(t),N(t.step-1),1===t.step&&(null===t||void 0===t||null===(i=t.borrower)||void 0===i?void 0:i.address)&&R(null===t||void 0===t||null===(l=t.borrower)||void 0===l?void 0:l.address),2===t.step&&null!==t&&void 0!==t&&t.cosigner&&(null===t||void 0===t||null===(s=t.cosigner)||void 0===s?void 0:s.length)>0)for(d=0;d<(null===t||void 0===t||null===(u=t.cosigner)||void 0===u?void 0:u.length);d++)(null===t||void 0===t||null===(c=t.cosigner)||void 0===c||null===(v=c[d])||void 0===v?void 0:v.address)&&R(null===t||void 0===t||null===(p=t.cosigner)||void 0===p||null===(g=p[d])||void 0===g?void 0:g.address);case 8:case"end":return e.stop()}}),e)})))(),function(){return n.abort()}}),[]);var W=function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n){var r,t,o,l,s,d;return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=null!==n&&void 0!==n&&n.loanType?Number(null===n||void 0===n?void 0:n.loanType):Number(null===O||void 0===O?void 0:O.loanType),t=(0,j.gQ)(),o=t.signal,e.next=4,(0,C.kh)((0,i.Z)((0,i.Z)({},n),{},{step:Number(null===O||void 0===O?void 0:O.step),leadId:Number(null===O||void 0===O?void 0:O.leadId),loanType:r}),o);case 4:if(l=e.sent,s=l.data,d=l.error,null===s||void 0===s||!s.abort){e.next=9;break}return e.abrupt("return");case 9:d?Z.Am.error(s.message):(N(s.step-1),F(s),4===(null===s||void 0===s?void 0:s.step)&&Z.Am.success(null===s||void 0===s?void 0:s.message));case 10:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}();(0,x.useEffect)((function(){var e=(0,j.gQ)(),n=e.controller,r=e.signal;return(0,l.Z)((0,a.Z)().mark((function e(){return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(1!==O.step){e.next=6;break}return e.t0=V,e.next=4,(0,C.fv)(r);case 4:e.t1=e.sent.data,(0,e.t0)("SET_LOAN_TYPE",e.t1);case 6:if(1!==O.step&&2!==O.step){e.next=22;break}return e.t2=V,e.next=10,(0,C.ox)(r,"?showOnAddress=YES");case 10:return e.t3=e.sent.data,(0,e.t2)("SET_COUNTRY",e.t3),e.t4=V,e.next=15,(0,C.nZ)(r);case 15:return e.t5=e.sent.data,(0,e.t4)("SET_STATE",e.t5),e.t6=V,e.next=20,(0,C.BC)(r);case 20:e.t7=e.sent.data,(0,e.t6)("SET_COURSES",e.t7);case 22:if(2!==O.step){e.next=38;break}return e.t8=V,e.next=26,(0,C.SL)(r);case 26:return e.t9=e.sent.data,(0,e.t8)("SET_EMPLOYMENT",e.t9),e.t10=V,e.next=31,(0,C.ox)(r,"?showOnAddress=YES");case 31:return e.t11=e.sent.data,(0,e.t10)("SET_COURSE_COUNTRY",e.t11),e.t12=V,e.next=36,(0,C.Di)(r);case 36:e.t13=e.sent.data,(0,e.t12)("SET_RELATIONS",e.t13);case 38:case"end":return e.stop()}}),e)})))(),function(){return n.abort()}}),[O.step]);var q=function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n,r,t,l,s,d){var u,c,v,p,g,f,m;return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return u=n.target.files,_((0,i.Z)((0,i.Z)({},Y),{},(0,o.Z)({},"".concat(r,"-").concat(t),(0,i.Z)((0,i.Z)({},Y["".concat(r,"-").concat(t)]),{},(0,o.Z)({},s,!0))))),(c=new FormData).append("individualType",r),c.append("individualId",t),c.append("documentId",l),c.append("documentType",s),c.append("file",u[0]),c.append("leadId",O.leadId),c.append("step","3"),c.append("loanType",d),v=(0,j.gQ)(),p=v.signal,e.next=14,(0,C.kh)(c,p,!0);case 14:if(g=e.sent,f=g.error,null===(m=g.data)||void 0===m||!m.abort){e.next=19;break}return e.abrupt("return");case 19:f?(Z.Am.error(m.message),_((0,i.Z)((0,i.Z)({},Y),{},(0,o.Z)({},"".concat(r,"-").concat(t),(0,i.Z)((0,i.Z)({},Y["".concat(r,"-").concat(t)]),{},(0,o.Z)({},s,!1)))))):(N(m.step-1),F(m),_((0,i.Z)((0,i.Z)({},Y),{},(0,o.Z)({},"".concat(r,"-").concat(t),(0,i.Z)((0,i.Z)({},Y["".concat(r,"-").concat(t)]),{},(0,o.Z)({},s,!1))))),Z.Am.success("File uploaded successfully"));case 20:case"end":return e.stop()}}),e)})));return function(n,r,t,o,i,a){return e.apply(this,arguments)}}(),D=function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n){var r,t;return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,j.gQ)(),t=r.signal,e.t0=V,e.next=4,(0,C.hw)(n.target.value,t);case 4:e.t1=e.sent.data,(0,e.t0)("SET_CITY",e.t1);case 6:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}(),J=(0,x.useCallback)(function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n,r){var t,o,i,l;return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!n){e.next=13;break}return t=(0,j.gQ)(),o=t.signal,e.next=4,(0,C.Q$)({individualId:n,leadId:r},o);case 4:if(i=e.sent,l=i.data,i.error){e.next=12;break}return Z.Am.success(l.message),e.abrupt("return",!0);case 12:Z.Am.error(l.message);case 13:case"end":return e.stop()}}),e)})));return function(n,r){return e.apply(this,arguments)}}(),[]),M=function(e){var n,r=(0,t.Z)(e.borrower.fileObject);try{for(r.s();!(n=r.n()).done;){var o=n.value;if(1==o.requiredIndividualType&&!o.documentPath)return Z.Am.warning("Borrower ".concat(null===o||void 0===o?void 0:o.documentName," is required")),!1}}catch(d){r.e(d)}finally{r.f()}if(null!==e&&void 0!==e&&e.cosigner&&e.cosigner.length>0)for(var i=0;i<e.cosigner.length;i++)for(var a=0;a<e.cosigner[i].fileObject.length;a++){var l,s=null===(l=e.cosigner[i])||void 0===l?void 0:l.fileObject[a];if(1==(null===s||void 0===s?void 0:s.requiredIndividualType)&&!s.documentPath)return Z.Am.warning("Cosigner ".concat(i+1," ").concat(null===s||void 0===s?void 0:s.documentName," is required")),!1}return!0},z=function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n,r){return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:e.t0=r,e.next=0===e.t0?3:1===e.t0?6:2===e.t0?9:14;break;case 3:return e.next=5,(0,j.Et)(j.Mo,(0,i.Z)((0,i.Z)({},n),{},{borrower:(0,i.Z)((0,i.Z)({},n.borrower),{},{loanAmount:parseInt(n.borrower.loanAmount)})}),function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n){return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,W(n);case 2:return e.abrupt("return",e.sent);case 3:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}());case 5:case 8:return e.abrupt("return",e.sent);case 6:return e.next=8,(0,j.Et)((0,j.vG)(Number(n.loanType)),n,function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n){return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,W(n);case 2:return e.abrupt("return",e.sent);case 3:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}(),2);case 9:if(!M(n)){e.next=13;break}return e.next=13,W({submit:!0});case 13:return e.abrupt("return",null);case 14:return e.abrupt("break",15);case 15:case"end":return e.stop()}}),e)})));return function(n,r){return e.apply(this,arguments)}}(),U=function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n){var r,t,o;return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,j.gQ)(),t=r.signal,e.next=3,(0,C.Mu)(n,t);case 3:return(o=e.sent).error?(o.error=!0,o.data.message="Something went wrong!!!"):(F(o.data),o.error=!1,o.data.message="Cosigner consent given successfully"),e.abrupt("return",o);case 6:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}(),H=function(){switch(S){case 0:return(0,T.jsx)(w.tP,{initValues:O,handleSelectionChange:D});case 1:return(0,T.jsx)(w.yT,{initValues:O,removeCosigner:J,handleSelectionChange:D,verifyCosignerOTP:U});case 2:return(0,T.jsx)(w.nm,{handleFile:q,docloading:Y})}},G=function(){var e=(0,l.Z)((0,a.Z)().mark((function e(){var n,r,t,o,i,l,s,d,u,c,v,p,g,f;return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,j.gQ)(),t=r.signal,e.next=3,(0,C.kh)({leadId:Number(null===O||void 0===O?void 0:O.leadId),step:Number((null===O||void 0===O?void 0:O.step)-2),loanType:Number(O.loanType),borrower:{id:null===O||void 0===O||null===(n=O.borrower)||void 0===n?void 0:n.id},validate:!1},t);case 3:if(o=e.sent,i=o.data,o.error)Z.Am.error(i.message);else if(N(i.step-1),F(i),1===i.step&&(null===i||void 0===i||null===(l=i.borrower)||void 0===l?void 0:l.address)&&R(null===i||void 0===i||null===(s=i.borrower)||void 0===s?void 0:s.address),2===i.step&&null!==i&&void 0!==i&&i.cosigner&&(null===i||void 0===i||null===(d=i.cosigner)||void 0===d?void 0:d.length)>0)for(u=0;u<(null===i||void 0===i||null===(c=i.cosigner)||void 0===c?void 0:c.length);u++)(null===i||void 0===i||null===(v=i.cosigner)||void 0===v||null===(p=v[u])||void 0===p?void 0:p.address)&&R(null===i||void 0===i||null===(g=i.cosigner)||void 0===g||null===(f=g[u])||void 0===f?void 0:f.address);case 7:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}();return(0,T.jsxs)(T.Fragment,{children:[(0,T.jsx)("div",{className:"mx-5",children:(0,T.jsx)(u.Z,{variant:"text",onClick:function(){return B("/dashboard/home")},children:(0,T.jsx)(d.Z,{})})}),(0,T.jsx)(c.Z,{component:v.Z,p:5,children:(0,T.jsxs)(p.Z,{component:v.Z,p:3,children:[S!==k.length&&(0,T.jsx)(g.Z,{alternativeLabel:!0,activeStep:S,sx:{mb:6},children:k.map((function(e,n){return(0,x.createElement)(f.Z,(0,i.Z)((0,i.Z)({},{}),{},{key:n}),(0,T.jsx)(m.Z,(0,i.Z)((0,i.Z)({},{}),{},{children:e})))}))}),S===k.length?(0,T.jsx)(w.oJ,{values:O}):(0,T.jsx)(T.Fragment,{children:(0,T.jsx)(h.J9,{initialValues:(0,i.Z)((0,i.Z)({},O),{},{borrower:(0,i.Z)((0,i.Z)({},O.borrower),{},{id:localStorage.getItem("xtpt")})}),enableReinitialize:!0,onSubmit:function(){var e=(0,l.Z)((0,a.Z)().mark((function e(n){return(0,a.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(null!==O&&void 0!==O&&O.allCosignerConsentGiven||1!=S){e.next=3;break}return Z.Am.warning("Please complete the consent of all the cosigners to proceed the step"),e.abrupt("return");case 3:return e.next=5,z(n,S);case 5:case"end":return e.stop()}}),e)})));return function(n){return e.apply(this,arguments)}}(),children:function(e){var n=e.handleSubmit;return(0,T.jsxs)("form",{onSubmit:n,children:[H(),(0,T.jsxs)(v.Z,{sx:{display:"flex",flexDirection:"row",pt:2,alignItems:"center",justifyContent:"center"},children:[0!==S&&(0,T.jsx)(u.Z,{color:"inherit",onClick:G,sx:{mr:1},children:"Back"}),(0,T.jsx)(u.Z,{color:"primary",type:"submit",variant:"contained",children:1!=S||null!==O&&void 0!==O&&O.allCosignerConsentGiven?S===k.length-1?"Submit":"Next":"Save"})]})]})}})})]})})]})}}}]);
//# sourceMappingURL=327.2b6d61e5.chunk.js.map