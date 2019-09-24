(window.webpackJsonp=window.webpackJsonp||[]).push([[8],{202:function(t,e,a){"use strict";a.r(e);var s=a(0),r=Object(s.a)({},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("ContentSlotsDistributor",{attrs:{"slot-key":t.$parent.slotKey}},[a("h1",{attrs:{id:"win32model"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#win32model","aria-hidden":"true"}},[t._v("#")]),t._v(" Win32Model")]),t._v(" "),a("p",[t._v("The "),a("code",[t._v("Win32Model")]),t._v(" is the base class for all of the models that represent the\n"),a("a",{attrs:{href:"https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-provider",target:"_blank",rel:"noopener noreferrer"}},[t._v("Win32 provider classes"),a("OutboundLink")],1),t._v(". Each model is\ncomposed of either another model or classes that represent\n"),a("a",{attrs:{href:"https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-wmi-provider",target:"_blank",rel:"noopener noreferrer"}},[t._v("CIM providers"),a("OutboundLink")],1),t._v(".")]),t._v(" "),a("h2",{attrs:{id:"example-composition"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#example-composition","aria-hidden":"true"}},[t._v("#")]),t._v(" Example Composition")]),t._v(" "),a("div",{staticClass:"language- line-numbers-mode"},[a("pre",{pre:!0,attrs:{class:"language-text"}},[a("code",[t._v("PHPWinTools\\WmiScripting\\Models\\UserAccount\n    → Models\\Account\n        → CIM\\CimLogicalElement\n            → CIM\\CimManagedSystemElement\n                → Models\\Win32Model\n")])]),t._v(" "),a("div",{staticClass:"line-numbers-wrapper"},[a("span",{staticClass:"line-number"},[t._v("1")]),a("br"),a("span",{staticClass:"line-number"},[t._v("2")]),a("br"),a("span",{staticClass:"line-number"},[t._v("3")]),a("br"),a("span",{staticClass:"line-number"},[t._v("4")]),a("br"),a("span",{staticClass:"line-number"},[t._v("5")]),a("br")])]),a("h2",{attrs:{id:"properties-and-attributes"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#properties-and-attributes","aria-hidden":"true"}},[t._v("#")]),t._v(" Properties and Attributes")]),t._v(" "),a("p",[t._v("While each model has all of its possible properties defined, the intended method to get the value of these properties\nis "),a("a",{attrs:{href:"#getattribute"}},[a("code",[t._v("getAttribute")])]),t._v(". This allows for mutating or "),a("a",{attrs:{href:"#attribute-casting"}},[t._v("casting")]),t._v(" the value upon retrieval\nvia an "),a("a",{attrs:{href:"#attribute-methods"}},[t._v("attribute method")]),t._v(", as well as, defining "),a("a",{attrs:{href:"#calculated-attributes"}},[t._v("calculated attributes")]),t._v(".")]),t._v(" "),a("p",[t._v("There are also a couple of properties that should be considered when extending from an existing model.")]),t._v(" "),a("h3",{attrs:{id:"connection"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#connection","aria-hidden":"true"}},[t._v("#")]),t._v(" $connection")]),t._v(" "),a("h4",{attrs:{id:"protected-connection-default"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#protected-connection-default","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("protected $connection = 'default'")])]),t._v(" "),a("p",[t._v("This is the name of connection that should be used when calling "),a("a",{attrs:{href:"#query"}},[a("code",[t._v("query")])]),t._v(" and "),a("a",{attrs:{href:"#all"}},[a("code",[t._v("all")])]),t._v("\nwithout providing a value for "),a("code",[t._v("$connection")]),t._v(".")]),t._v(" "),a("h3",{attrs:{id:"wmi-class-name"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#wmi-class-name","aria-hidden":"true"}},[t._v("#")]),t._v(" $wmi_class_name")]),t._v(" "),a("h4",{attrs:{id:"protected-wmi-class-name"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#protected-wmi-class-name","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("protected $wmi_class_name")])]),t._v(" "),a("p",[t._v("This is an optional property, but only if the model you are calling extends an existing "),a("code",[t._v("Win32Model")]),t._v(". If you do not\ndefine this property and a class name cannot determine the WMI class name an exception will be thrown.")]),t._v(" "),a("h3",{attrs:{id:"attribute-casting"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#attribute-casting","aria-hidden":"true"}},[t._v("#")]),t._v(" $attribute_casting")]),t._v(" "),a("h4",{attrs:{id:"protected-attribute-casting"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#protected-attribute-casting","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("protected $attribute_casting = []")])]),t._v(" "),a("p",[t._v("Attributes will attempted to be casted to the given type if defined within this array. All definitions of\n"),a("code",[t._v("$attribute_casting")]),t._v(" are merged from the models ancestors by default. This allows you to define "),a("code",[t._v("$attribute_casting")]),t._v(" in\nboth a parent and a child without risk of having the values overridden. In the case where both the child and parent\nclass both define the same attribute to be casted the child casting will be used.")]),t._v(" "),a("div",{staticClass:"language-php line-numbers-mode"},[a("pre",{pre:!0,attrs:{class:"language-php"}},[a("code",[t._v("Available castings\n\n"),a("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("protected")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$attribute_casting")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("[")]),t._v("\n    "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'attribute'")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v(">")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'array'")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v("\n    "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'attribute'")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v(">")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'bool'")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v("\n    "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'attribute'")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v(">")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'int'")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v("\n    "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'attribute'")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),a("span",{pre:!0,attrs:{class:"token operator"}},[t._v(">")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'string'")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v("\n"),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("]")]),t._v("\n\n")])]),t._v(" "),a("div",{staticClass:"line-numbers-wrapper"},[a("span",{staticClass:"line-number"},[t._v("1")]),a("br"),a("span",{staticClass:"line-number"},[t._v("2")]),a("br"),a("span",{staticClass:"line-number"},[t._v("3")]),a("br"),a("span",{staticClass:"line-number"},[t._v("4")]),a("br"),a("span",{staticClass:"line-number"},[t._v("5")]),a("br"),a("span",{staticClass:"line-number"},[t._v("6")]),a("br"),a("span",{staticClass:"line-number"},[t._v("7")]),a("br"),a("span",{staticClass:"line-number"},[t._v("8")]),a("br"),a("span",{staticClass:"line-number"},[t._v("9")]),a("br")])]),a("h3",{attrs:{id:"merge-parent-casting"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#merge-parent-casting","aria-hidden":"true"}},[t._v("#")]),t._v(" $merge_parent_casting")]),t._v(" "),a("h4",{attrs:{id:"protected-merge-parent-casting-true"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#protected-merge-parent-casting-true","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("protected $merge_parent_casting = true")])]),t._v(" "),a("p",[t._v("If set to "),a("code",[t._v("false")]),t._v(" then the "),a("code",[t._v("$attribute_casting")]),t._v(" will not be merged from the ancestors and only the castings defined\nwithin the child's "),a("code",[t._v("$attribute_casting")]),t._v(" will be considered.")]),t._v(" "),a("h3",{attrs:{id:"hidden-attributes"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#hidden-attributes","aria-hidden":"true"}},[t._v("#")]),t._v(" $hidden_attributes")]),t._v(" "),a("h4",{attrs:{id:"protected-hidden-attributes"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#protected-hidden-attributes","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("protected $hidden_attributes = []")])]),t._v(" "),a("p",[t._v("Any attributes listed in this array will not be included when casting a "),a("a",{attrs:{href:"#toarray"}},[t._v("model to array")]),t._v(". Like\n"),a("a",{attrs:{href:"#attribute-casting"}},[a("code",[t._v("$attribute_casting")])]),t._v(", the values in this array are merged from the model's ancestors.")]),t._v(" "),a("h3",{attrs:{id:"merge-parent-hidden-attributes"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#merge-parent-hidden-attributes","aria-hidden":"true"}},[t._v("#")]),t._v(" $merge_parent_hidden_attributes")]),t._v(" "),a("h4",{attrs:{id:"protected-merge-parent-hidden-attributes-true"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#protected-merge-parent-hidden-attributes-true","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("protected $merge_parent_hidden_attributes = true")])]),t._v(" "),a("p",[t._v("If set to "),a("code",[t._v("false")]),t._v(" then the "),a("code",[t._v("$hidden_attributes")]),t._v(" will not be merged from the ancestors and only the castings defined\nwithin the child's "),a("code",[t._v("$hidden_attributes")]),t._v(" will be considered.")]),t._v(" "),a("h3",{attrs:{id:"calculated-attributes"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#calculated-attributes","aria-hidden":"true"}},[t._v("#")]),t._v(" Calculated Attributes")]),t._v(" "),a("p",[t._v("Calculated attributes are simply "),a("a",{attrs:{href:"#attribute-methods"}},[t._v("attribute methods")]),t._v(" without an associated property.")]),t._v(" "),a("h2",{attrs:{id:"methods"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#methods","aria-hidden":"true"}},[t._v("#")]),t._v(" Methods")]),t._v(" "),a("p",[t._v("Below are the most useful methods available to all classes that extend "),a("code",[t._v("Win32Model")]),t._v(".")]),t._v(" "),a("h3",{attrs:{id:"attribute-methods"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#attribute-methods","aria-hidden":"true"}},[t._v("#")]),t._v(" Attribute Methods")]),t._v(" "),a("h4",{attrs:{id:"getsomepropertyattribute-value"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#getsomepropertyattribute-value","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("getSomePropertyAttribute($value)")])]),t._v(" "),a("h4",{attrs:{id:"getsomepropertyattribute"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#getsomepropertyattribute","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("getSomePropertyAttribute()")])]),t._v(" "),a("p",[t._v("Attribute methods are getter methods that are named after the associated property sandwiched by "),a("code",[t._v("get")]),t._v(" and "),a("code",[t._v("Attribute")]),t._v(".\nThis is very similar and absolutely inspired by Laravel's Eloquent attribute mutator.")]),t._v(" "),a("div",{staticClass:"language-php line-numbers-mode"},[a("pre",{pre:!0,attrs:{class:"language-php"}},[a("code",[a("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("public")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("function")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token function"}},[t._v("getSomePropertyAttribute")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),a("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$value")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),t._v("\n"),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v("\n    "),a("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("return")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token function"}},[t._v("strtoupper")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),a("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$value")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n"),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n")])]),t._v(" "),a("div",{staticClass:"line-numbers-wrapper"},[a("span",{staticClass:"line-number"},[t._v("1")]),a("br"),a("span",{staticClass:"line-number"},[t._v("2")]),a("br"),a("span",{staticClass:"line-number"},[t._v("3")]),a("br"),a("span",{staticClass:"line-number"},[t._v("4")]),a("br")])]),a("p",[t._v("If there is a property that matches the name within "),a("code",[t._v("get")]),t._v(" "),a("code",[t._v("Attribute")]),t._v(" then the value of that property will be passed to\nthe method otherwise nothing is passed. This can be used to create calculated attributes that have may not have an\nassociated property.")]),t._v(" "),a("p",[t._v("These are evaluated during "),a("a",{attrs:{href:"#getattribute"}},[a("code",[t._v("getAttribute")])]),t._v(" calls and when calling "),a("a",{attrs:{href:"#toarray"}},[a("code",[t._v("toArray")])]),t._v(".")]),t._v(" "),a("h3",{attrs:{id:"mapconstant"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#mapconstant","aria-hidden":"true"}},[t._v("#")]),t._v(" mapConstant")]),t._v(" "),a("h4",{attrs:{id:"protected-function-mapconstant-string-mapping-string-class-constant"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#protected-function-mapconstant-string-mapping-string-class-constant","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("protected function mapConstant(string $mapping_string_class, $constant)")])]),t._v(" "),a("p",[t._v("This method can be used with an "),a("a",{attrs:{href:"#attribute-methods"}},[t._v("attribute method")]),t._v(" to convert a property that typically returns\nan integer that is mappable to a string.")]),t._v(" "),a("p",[t._v("The "),a("code",[t._v("$mapping_string_class")]),t._v(" parameter expects a class name of a class that extends "),a("code",[t._v("MappingString\\Mappings")]),t._v(" and are\nmeant to represent\n"),a("a",{attrs:{href:"https://docs.microsoft.com/en-us/windows/win32/wmisdk/standard-qualifiers#mappingstrings",target:"_blank",rel:"noopener noreferrer"}},[t._v("MappingStrings"),a("OutboundLink")],1),t._v(".")]),t._v(" "),a("h3",{attrs:{id:"query"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#query","aria-hidden":"true"}},[t._v("#")]),t._v(" query")]),t._v(" "),a("h4",{attrs:{id:"win32model-query-connection-null"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#win32model-query-connection-null","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("Win32Model::query($connection = null)")])]),t._v(" "),a("p",[t._v("This method allows you to query a model directly and returns an instance of "),a("code",[t._v("Query\\Builder")]),t._v(". It accepts a "),a("code",[t._v("$connection")]),t._v("\nargument that can be provided in a number of ways.")]),t._v(" "),a("div",{staticClass:"language-php line-numbers-mode"},[a("pre",{pre:!0,attrs:{class:"language-php"}},[a("code",[a("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// Uses default model connection or falls back to default Config connection.")]),t._v("\nWin32Model"),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),a("span",{pre:!0,attrs:{class:"token function"}},[t._v("query")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),a("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// Finds the connection by its name.")]),t._v("\nWin32Model"),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),a("span",{pre:!0,attrs:{class:"token function"}},[t._v("query")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'named_connection'")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),a("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// Uses the connection as is.")]),t._v("\nWin32Model"),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),a("span",{pre:!0,attrs:{class:"token function"}},[t._v("query")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),t._v("Connection"),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),a("span",{pre:!0,attrs:{class:"token function"}},[t._v("simple")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'computer'")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'user'")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v(" "),a("span",{pre:!0,attrs:{class:"token single-quoted-string string"}},[t._v("'password'")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),a("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n")])]),t._v(" "),a("div",{staticClass:"line-numbers-wrapper"},[a("span",{staticClass:"line-number"},[t._v("1")]),a("br"),a("span",{staticClass:"line-number"},[t._v("2")]),a("br"),a("span",{staticClass:"line-number"},[t._v("3")]),a("br"),a("span",{staticClass:"line-number"},[t._v("4")]),a("br"),a("span",{staticClass:"line-number"},[t._v("5")]),a("br"),a("span",{staticClass:"line-number"},[t._v("6")]),a("br"),a("span",{staticClass:"line-number"},[t._v("7")]),a("br"),a("span",{staticClass:"line-number"},[t._v("8")]),a("br")])]),a("p",[t._v("If you pass a "),a("code",[t._v("Connection")]),t._v(" instance it will not be stored in the "),a("code",[t._v("Config")]),t._v(" container. This can be useful if you have a\nnumber of connections you do not wish to persist.")]),t._v(" "),a("h3",{attrs:{id:"all"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#all","aria-hidden":"true"}},[t._v("#")]),t._v(" all")]),t._v(" "),a("h4",{attrs:{id:"win32model-all-connection-null"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#win32model-all-connection-null","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("Win32Model::all($connection = null)")])]),t._v(" "),a("p",[t._v("This method will call "),a("code",[t._v("all")]),t._v(" on the "),a("code",[t._v("Query\\Builder")]),t._v(" and return an instance of "),a("code",[t._v("ModelCollection")]),t._v(". You can pass a\nconnection to this method in the same ways that are available to "),a("a",{attrs:{href:"#query"}},[a("code",[t._v("query")])]),t._v(".")]),t._v(" "),a("h3",{attrs:{id:"getattribute"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#getattribute","aria-hidden":"true"}},[t._v("#")]),t._v(" getAttribute")]),t._v(" "),a("h4",{attrs:{id:"model-getattribute-attribute-default-null"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#model-getattribute-attribute-default-null","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("$model->getAttribute($attribute, $default = null)")])]),t._v(" "),a("p",[t._v("This is the preferred method for retrieving values from a model. When using this method the property will be evaluated\nfor "),a("a",{attrs:{href:"#attribute-casting"}},[t._v("castings")]),t._v(", "),a("a",{attrs:{href:"#attribute-methods"}},[t._v("attribute methods")]),t._v(", and\n"),a("a",{attrs:{href:"#attribute-methods"}},[t._v("calculated attributes")]),t._v(".")]),t._v(" "),a("p",[t._v("The second parameter, "),a("code",[t._v("$default")]),t._v(", allows you to set a default value if no value could be determined otherwise "),a("code",[t._v("null")]),t._v(" is\nreturned.")]),t._v(" "),a("h3",{attrs:{id:"toarray"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#toarray","aria-hidden":"true"}},[t._v("#")]),t._v(" toArray")]),t._v(" "),a("h4",{attrs:{id:"model-toarray"}},[a("a",{staticClass:"header-anchor",attrs:{href:"#model-toarray","aria-hidden":"true"}},[t._v("#")]),t._v(" "),a("code",[t._v("$model->toArray()")])]),t._v(" "),a("p",[t._v("Transforms the model class into an array.")]),t._v(" "),a("p",[t._v("This is accomplished by evaluating each property, "),a("a",{attrs:{href:"#attribute-methods"}},[t._v("attribute method")]),t._v(", "),a("a",{attrs:{href:"#attribute-casting"}},[t._v("casting")]),t._v(",\nand "),a("a",{attrs:{href:"#hidden-attribute"}},[t._v("hidden attribute")]),t._v(".")])])},[],!1,null,null,null);e.default=r.exports}}]);