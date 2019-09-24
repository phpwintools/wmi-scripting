(window.webpackJsonp=window.webpackJsonp||[]).push([[10],{207:function(e,t,o){"use strict";o.r(t);var r=o(0),n=Object(r.a)({},function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("ContentSlotsDistributor",{attrs:{"slot-key":e.$parent.slotKey}},[o("h1",{attrs:{id:"how-it-works"}},[o("a",{staticClass:"header-anchor",attrs:{href:"#how-it-works","aria-hidden":"true"}},[e._v("#")]),e._v(" How It Works")]),e._v(" "),o("p",[e._v("The basic goal of this library is to make querying information from Windows systems on your network as easy as possible.\nThis is accomplished by adopting an active record approach similar to\n"),o("a",{attrs:{href:"https://laravel.com/docs/5.8/eloquent",target:"_blank",rel:"noopener noreferrer"}},[e._v("Laravel's Eloquent"),o("OutboundLink")],1),e._v(".")]),e._v(" "),o("p",[e._v("The WMI Scripting library consists of models that represent the\n"),o("a",{attrs:{href:"https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-provider",target:"_blank",rel:"noopener noreferrer"}},[e._v("WMI Win32 Provider classes"),o("OutboundLink")],1),e._v("\nand a basic query builder using "),o("a",{attrs:{href:"https://docs.microsoft.com/en-us/windows/win32/wmisdk/querying-with-wql",target:"_blank",rel:"noopener noreferrer"}},[e._v("WQL"),o("OutboundLink")],1),e._v("\nas it's target language.")]),e._v(" "),o("p",[e._v("I also take advantage of "),o("a",{attrs:{href:"https://laravel.com/docs/5.8/collections",target:"_blank",rel:"noopener noreferrer"}},[e._v("Laravel's Collections"),o("OutboundLink")],1),e._v(" via the extraction done by\n"),o("a",{attrs:{href:"https://github.com/tightenco/collect",target:"_blank",rel:"noopener noreferrer"}},[e._v("TightenCo"),o("OutboundLink")],1),e._v(", so you are not bound directly to the Laravel framework. Anytime you\nquery a model you will get back an instance of "),o("code",[e._v("ModelCollection")]),e._v(" which extends Laravel's "),o("code",[e._v("Collection")]),e._v(". This allows for\nfluent access to this data: "),o("code",[e._v("$modelCollection->map->getAttribute('name);")]),e._v(" returns a collection of only the model names.")]),e._v(" "),o("p",[e._v("The Win32Models extend either another Win32 model, or more likely, a\n"),o("a",{attrs:{href:"https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cimwin32-wmi-providers",target:"_blank",rel:"noopener noreferrer"}},[e._v("CIM"),o("OutboundLink")],1),e._v(" object. This follows the way\nthat Win32 models are composed.")]),e._v(" "),o("h2",{attrs:{id:"todo"}},[o("a",{staticClass:"header-anchor",attrs:{href:"#todo","aria-hidden":"true"}},[e._v("#")]),e._v(" Todo")]),e._v(" "),o("p",[e._v("I'm still working through the API and some of the core code to make this library as clean and resilient as possible.\nThe current wish list consists of at least the following:")]),e._v(" "),o("ul",[o("li",[e._v("Near 100% test coverage")]),e._v(" "),o("li",[e._v("Expand testing system")]),e._v(" "),o("li",[e._v("Event system")])])])},[],!1,null,null,null);t.default=n.exports}}]);