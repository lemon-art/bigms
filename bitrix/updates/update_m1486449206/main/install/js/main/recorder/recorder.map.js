{"version":3,"file":"recorder.min.js","sources":["recorder.js"],"names":["window","BX","supportedTypes","audio/mp3","states","idle","recording","encoding","Recorder","stream","options","MediaStream","type","isPlainObject","this","isTypeSupported","state","audioContext","mediaStreamNode","scriptNode","worker","Worker","postMessage","action","onmessage","e","data","onCustomEvent","result","bind","isSupported","Blob","URL","AudioContext","webkitAudioContext","prototype","start","self","createScriptProcessor","connect","destination","createMediaStreamSource","onaudioprocess","event","input","inputBuffer","getChannelData","stop","disconnect","close","dispose"],"mappings":"CAKA,SAAUA,GAET,SAAWC,IAAW,WAAM,YAAa,MAEzC,IAAIC,IACHC,YAAa,KAGd,IAAIC,IACHC,KAAM,EACNC,UAAW,EACXC,SAAU,EAGXN,IAAGO,SAAW,SAASC,EAAQC,GAE9B,IAAID,YAAkBT,GAAOW,YAC5B,KAAM,oCAEP,KAAIV,GAAGW,KAAKC,cAAcH,GACzBA,IAEDI,MAAKL,OAASA,CACdK,MAAKJ,SACJE,KAAOF,EAAQE,MAAQX,GAAGO,SAASO,gBAAgBL,EAAQE,MAAQF,EAAQE,KAAO,YAGnFE,MAAKE,MAAQZ,EAAOC,IAEpBS,MAAKG,aAAe,IACpBH,MAAKI,gBAAkB,IACvBJ,MAAKK,WAAa,IAElBL,MAAKM,OAAS,GAAIpB,GAAOqB,OAAO,sCAChCP,MAAKM,OAAOE,aAAaC,OAAQ,OAAQX,KAAME,KAAKJ,QAAQE,MAE5DE,MAAKM,OAAOI,UAAY,SAASC,GAEhC,OAAOA,EAAEC,KAAKH,QAEb,IAAK,SACJtB,GAAG0B,cAAcb,KAAM,QAASW,EAAEC,KAAKE,QACvC,SAEDC,KAAKf,MAIRb,IAAGO,SAASsB,YAAc,WAEzB,MAAO9B,GAAO+B,MAAQ/B,EAAOqB,QAAUrB,EAAOgC,KAAOhC,EAAOW,cAAgBX,EAAOiC,cAAgBjC,EAAOkC,oBAG3GjC,IAAGO,SAASO,gBAAkB,SAASH,GAEtC,MAAQV,GAAeU,KAAU,KAGlCX,IAAGO,SAAS2B,UAAUC,MAAQ,WAE7B,GAAIC,GAAOvB,IACX,IAAGA,KAAKE,QAAUZ,EAAOC,KACxB,KAAM,wCAEPS,MAAKG,aAAe,IAAKjB,EAAOiC,cAAgBjC,EAAOkC,mBACvDpB,MAAKK,WAAaL,KAAKG,aAAaqB,sBAAsB,MAAO,EAAG,EACpExB,MAAKK,WAAWoB,QAAQzB,KAAKG,aAAauB,YAE1C1B,MAAKI,gBAAkBJ,KAAKG,aAAawB,wBAAwB3B,KAAKL,OACtEK,MAAKI,gBAAgBqB,QAAQzB,KAAKK,WAElCL,MAAKK,WAAWuB,eAAiB,SAASC,GAEzC,GAAGN,EAAKrB,QAAUZ,EAAOE,UACxB,MAED,KAAI+B,EAAKjB,OACR,MAED,IAAIwB,GAAQD,EAAME,YAAYC,eAAe,EAC7CT,GAAKjB,OAAOE,aACXC,OAAQ,SACRqB,MAAOA,IAGTP,GAAKjB,OAAOE,aAAaC,OAAQ,SAEjCT,MAAKE,MAAQZ,EAAOE,UAIrBL,IAAGO,SAAS2B,UAAUY,KAAO,WAE5B,GAAGjC,KAAKE,QAAUZ,EAAOE,UACxB,KAAM,wCAEPQ,MAAKM,OAAOE,aACXC,OAAQ,QAGT,IAAGT,KAAKK,WACPL,KAAKK,WAAW6B,YAEjB,IAAGlC,KAAKI,gBACPJ,KAAKI,gBAAgB8B,YAEtB,IAAGlC,KAAKG,aACPH,KAAKG,aAAagC,OAEnBnC,MAAKK,WAAa,IAClBL,MAAKI,gBAAkB,IACvBJ,MAAKG,aAAe,IAEpBH,MAAKE,MAAQZ,EAAOC,KAGrBJ,IAAGO,SAAS2B,UAAUe,QAAU,WAE/BpC,KAAKL,OAAS,QAGbT"}