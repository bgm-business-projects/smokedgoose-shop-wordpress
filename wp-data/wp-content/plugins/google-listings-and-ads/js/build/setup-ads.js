"use strict";(globalThis.webpackChunkgoogle_listings_and_ads=globalThis.webpackChunkgoogle_listings_and_ads||[]).push([[167],{1939:(e,t,n)=>{n.r(t),n.d(t,{default:()=>O});var a=n(1609),o=n(6474),l=n(6476),s=n(7723),i=n(7539),c=n(7980),u=n(6473);const r=()=>(0,a.createElement)(i.A,{title:(0,s.__)("Set up your campaign","google-listings-and-ads"),helpButton:(0,a.createElement)(c.A,{eventContext:"setup-ads"}),backHref:(0,l.getNewPath)({},"/google/dashboard"),onBackButtonClick:()=>{(0,u.ce)("gla_setup_ads",{triggered_by:"back-button",action:"leave"})}});var g=n(8846),d=n(6087),m=n(7892),p=n(9370),_=n(3164),A=n(3704),h=n(9826),E=n(850),y=n(1245),b=n(458),C=n(6464),k=n(1378),f=n(8e3),S=n(3741),v=n(6575),w=n(1351);const P=e=>{const{onContinue:t=()=>{}}=e,{google:n}=(0,f.A)(),{googleAdsAccount:o}=(0,k.A)(),l=(0,w.A)();if(!n||"yes"===n.active&&!o)return(0,a.createElement)(S.A,null);const i=!l;return(0,a.createElement)(p.A,null,(0,a.createElement)(_.A,{title:(0,s.__)("Set up your accounts","google-listings-and-ads"),description:(0,s.__)("Connect your Google account and your Google Ads account to set up a Performance Max campaign.","google-listings-and-ads")}),(0,a.createElement)(v.A,{title:(0,s.__)("Connect accounts","google-listings-and-ads"),description:(0,s.__)("Any campaigns created through this app will appear in your Google Ads account. You will be billed directly through Google.","google-listings-and-ads")},(0,a.createElement)(E.A,{size:"large"},(0,a.createElement)(y.Az,{googleAccount:n,hideAccountSwitch:!0,helper:(0,s.__)("This Google account is connected to your store’s product feed.","google-listings-and-ads")}),(0,a.createElement)(b.Ay,null),(0,a.createElement)(C.A,null))),(0,a.createElement)(h.A,null,(0,a.createElement)(A.A,null,(0,a.createElement)(m.A,{isPrimary:!0,disabled:i,onClick:t},(0,s.__)("Continue","google-listings-and-ads")))))};var B=n(7541),G=n(5992),T=n(8468),x=n(1203),F=n(6893),R=n(1968),D=n(1650),N=n(5847),z=n(8519),V=n(8473),Y=n(4679),j=n(3905);const{APPROVED:q}=j.CX,H=()=>{const{billingStatus:e}=(0,F.A)(),[t,n]=(0,d.useState)(!1),[o,i]=(0,d.useState)(!1),[c,r]=(0,z.A)(),g=(0,R.A)(),{data:p}=(0,N.A)(),{highestDailyBudget:_,hasFinishedResolution:A}=(0,Y.A)(p),h={amount:_};(0,d.useEffect)((()=>{if(o){const e=(0,l.getNewPath)({guide:"campaign-creation-success"},"/google/dashboard");window.location.href=g+e}}),[o,g]);const E=t&&!o;return(0,D.A)((0,s.__)("You have unsaved campaign data. Are you sure you want to leave?","google-listings-and-ads"),E),p&&A?(0,a.createElement)(V.A,{initialCampaign:h,onChange:(e,t)=>{n(!(0,T.isEqual)(h,t))},onSubmit:e=>{const{amount:t}=e;(0,u.ce)("gla_launch_paid_campaign_button_click",{audiences:p.join(","),budget:t}),c(t,p,(()=>{i(!0)}))},recommendedDailyBudget:_},(0,a.createElement)(x.A,{headerTitle:(0,s.__)("Create your campaign","google-listings-and-ads"),context:"setup-ads",continueButton:t=>(0,a.createElement)(m.A,{isPrimary:!0,text:(0,s.__)("Create campaign","google-listings-and-ads"),disabled:!t.isValidForm||e?.status!==q,loading:r,onClick:t.handleSubmit})})):(0,a.createElement)(S.A,null)},M=()=>{const[e,t]=(0,d.useState)("1"),n=(0,d.useRef)(null),{hasFinishedResolution:o,hasGoogleAdsConnection:l}=(0,k.A)(),{hasAccess:i,hasFinishedResolution:c,step:r}=(0,G.A)();if((0,B.A)(u.T1,{context:u.lr,step:e}),null===n.current){if(!o||!c)return(0,a.createElement)(S.A,null);const e=l&&!0===i&&"conversion_action"!==r;n.current=e}const m=n=>{n<e&&((0,u.T)("gla_setup_ads",n),t(n))};let p=[{key:"1",label:(0,s.__)("Set up your accounts","google-listings-and-ads"),content:(0,a.createElement)(P,{onContinue:()=>{(()=>{const n=e;(0,u.dQ)("gla_setup_ads",n,"2"),t("2")})()}}),onClick:m},{key:"2",label:(0,s.__)("Create your campaign","google-listings-and-ads"),content:(0,a.createElement)(H,null),onClick:m}];return n.current&&(p.shift(),p=p.map(((e,t)=>({...e,key:(t+1).toString()})))),(0,a.createElement)(g.Stepper,{className:"gla-setup-stepper",currentStep:e,steps:p})},O=()=>((0,o.A)("full-page"),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(r,null),(0,a.createElement)(M,null)))}}]);