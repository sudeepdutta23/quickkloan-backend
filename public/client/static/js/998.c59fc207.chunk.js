"use strict";(self.webpackChunkquickloans=self.webpackChunkquickloans||[]).push([[998],{8998:function(e,n,a){a.r(n);var t=a(4165),r=a(5861),i=a(9439),s=a(3833),c=a(6205),o=a(1724),l=a(493),d=a(5021),u=a(6278),h=a(7064),v=a(9900),m=a(697),f=a(2791),x=a(3727),Z=a(9206),p=a(6388),g=a(7541),j=a(6152),z=a(7689),I=a(9085),w=(a(5462),a(4810)),M=a(2550),k=a(1780),b=a(706),L=a(933),H=a(6109),S=a(184);n.default=function(){var e=f.useState(!1),n=(0,i.Z)(e,2),a=n[0],V=n[1],C=f.useState([]),y=(0,i.Z)(C,2),A=y[0],N=y[1],F=(0,z.s0)(),W=M.kh.blogFeatureId,P=[{id:1,name:localStorage.getItem("adminUser"),path:"",icon:(0,S.jsx)(s.Z,{})},{id:2,name:"Home",path:"/admin/getAllLeads/all",icon:(0,S.jsx)(g.Z,{})},{id:3,name:"Master",path:"/admin/master",icon:(0,S.jsx)(Z.Z,{})},{id:4,name:"Services",path:"/admin/services",icon:(0,S.jsx)(x.Z,{})},{id:5,name:"About Us",path:"/admin/aboutUs",icon:(0,S.jsx)(p.Z,{})},{id:6,name:"Home Banner",path:"/admin/banner",icon:(0,S.jsx)(j.Z,{})},{id:7,name:"Blogs",featureId:W,path:"/admin/blogs",icon:(0,S.jsx)(o.Z,{})},{id:8,name:"Logout",path:"/adminLogin",icon:(0,S.jsx)(c.Z,{})}],R=(0,k.lm)().state.feature;(0,f.useEffect)((function(){var e=[];R&&(R.forEach((function(n){return 0===Number(n.isActive)&&e.push(n.id)})),e.forEach((function(e){var n=P.findIndex((function(n){return(null===n||void 0===n?void 0:n.featureId)==e}));-1!==n&&P.splice(n,1)}))),N(P)}),[R]);var E=function(){V(!a)},T=function(){var e=(0,r.Z)((0,t.Z)().mark((function e(){var n,a,r,i,s;return(0,t.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=(0,H.gQ)(),a=n.signal,e.next=3,(0,b.kS)(a);case 3:r=e.sent,i=r.error,s=r.data,i?I.Am.error(s.message):(localStorage.clear(),F("/adminLogin"),I.Am.success(s.message));case 7:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}(),U=(0,S.jsx)(l.Z,{children:A.length>0&&A.map((function(e,n){return(0,S.jsx)(d.ZP,{disablePadding:!0,onClick:function(){return function(e){switch(e.path){case"/adminLogin":T();break;case"":break;default:F(e.path)}}(e)},children:(0,S.jsxs)(u.Z,{children:[(0,S.jsx)(h.Z,{children:e.icon}),(0,S.jsx)(v.Z,{primary:e.name})]})},e.id)}))});return(0,S.jsxs)(m.Z,{sx:{display:"flex"},children:[(0,S.jsx)(L.WC,{children:(0,S.jsxs)(S.Fragment,{children:[(0,S.jsx)(w.iR,{handleDrawerToggle:E,drawerWidth:240}),(0,S.jsx)(w.t7,{drawerWidth:240,handleDrawerToggle:E,mobileOpen:a,children:U}),(0,S.jsx)(m.Z,{sx:{width:"100vw",py:10,justifyContent:"center",alignItems:"center",backgroundColor:"#efefef"},children:(0,S.jsx)(z.j3,{})})]})}),(0,S.jsx)(I.Ix,{})]})}},9206:function(e,n,a){var t=a(4836);n.Z=void 0;var r=t(a(5649)),i=a(184),s=(0,r.default)((0,i.jsx)("path",{d:"M20.5 6c-2.61.7-5.67 1-8.5 1s-5.89-.3-8.5-1L3 8c1.86.5 4 .83 6 1v13h2v-6h2v6h2V9c2-.17 4.14-.5 6-1l-.5-2zM12 6c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"}),"AccessibilityNew");n.Z=s},1724:function(e,n,a){var t=a(4836);n.Z=void 0;var r=t(a(5649)),i=a(184),s=(0,r.default)((0,i.jsx)("path",{d:"M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"}),"Assessment");n.Z=s},6388:function(e,n,a){var t=a(4836);n.Z=void 0;var r=t(a(5649)),i=a(184),s=(0,r.default)((0,i.jsx)("path",{d:"M21 8V7l-3 2-3-2v1l3 2 3-2zm1-5H2C.9 3 0 3.9 0 5v14c0 1.1.9 2 2 2h20c1.1 0 1.99-.9 1.99-2L24 5c0-1.1-.9-2-2-2zM8 6c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H2v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1zm8-6h-8V6h8v6z"}),"ContactMail");n.Z=s},6152:function(e,n,a){var t=a(4836);n.Z=void 0;var r=t(a(5649)),i=a(184),s=(0,r.default)((0,i.jsx)("path",{d:"M14.4 6 14 4H5v17h2v-7h5.6l.4 2h7V6z"}),"Flag");n.Z=s},7541:function(e,n,a){var t=a(4836);n.Z=void 0;var r=t(a(5649)),i=a(184),s=(0,r.default)((0,i.jsx)("path",{d:"M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"}),"Home");n.Z=s},3727:function(e,n,a){var t=a(4836);n.Z=void 0;var r=t(a(5649)),i=a(184),s=(0,r.default)((0,i.jsx)("path",{d:"M20 7h-4V5c0-.55-.22-1.05-.59-1.41C15.05 3.22 14.55 3 14 3h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm1 13.5-1-1 3-3-3-3 1-1 4 4-4 4z"}),"NextWeek");n.Z=s},6205:function(e,n,a){var t=a(4223),r=a(184);n.Z=(0,t.Z)((0,r.jsx)("path",{d:"m17 7-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"}),"Logout")},3833:function(e,n,a){var t=a(4223),r=a(184);n.Z=(0,t.Z)((0,r.jsx)("path",{d:"M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"}),"Person")},7064:function(e,n,a){var t=a(3366),r=a(7462),i=a(2791),s=a(9278),c=a(4419),o=a(6934),l=a(1402),d=a(6014),u=a(9480),h=a(184),v=["className"],m=(0,o.ZP)("div",{name:"MuiListItemIcon",slot:"Root",overridesResolver:function(e,n){var a=e.ownerState;return[n.root,"flex-start"===a.alignItems&&n.alignItemsFlexStart]}})((function(e){var n=e.theme,a=e.ownerState;return(0,r.Z)({minWidth:56,color:(n.vars||n).palette.action.active,flexShrink:0,display:"inline-flex"},"flex-start"===a.alignItems&&{marginTop:8})})),f=i.forwardRef((function(e,n){var a=(0,l.Z)({props:e,name:"MuiListItemIcon"}),o=a.className,f=(0,t.Z)(a,v),x=i.useContext(u.Z),Z=(0,r.Z)({},a,{alignItems:x.alignItems}),p=function(e){var n=e.alignItems,a=e.classes,t={root:["root","flex-start"===n&&"alignItemsFlexStart"]};return(0,c.Z)(t,d.f,a)}(Z);return(0,h.jsx)(m,(0,r.Z)({className:(0,s.Z)(p.root,o),ownerState:Z,ref:n},f))}));n.Z=f}}]);
//# sourceMappingURL=998.c59fc207.chunk.js.map