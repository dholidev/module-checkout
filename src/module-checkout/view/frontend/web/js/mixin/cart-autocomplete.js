define(["jquery","uiRegistry","underscore","Magento_Checkout/js/model/quote","Magento_Checkout/js/model/checkout-data-resolver","Magento_Checkout/js/model/address-converter","Magento_Checkout/js/checkout-data","Dholi_Geolocation/js/storage"],function(g,c,d,b,f,e,h,a){return function(i){c.async("checkoutProvider")(function(j){j.on("shippingAddress",function(m){try{var l,k;f.resolveEstimationAddress();l=b.isVirtual()?b.billingAddress():b.shippingAddress();if(l&&!l.postcode){if(!window.dholiClientAddress.geolocation){return}if(!a.hasData()){return}let geoAddress=a.getAddressData();l.postcode=geoAddress.postalCode;l.regionId=geoAddress.state;l.countryId=geoAddress.country;k=l.isEditable()?e.quoteAddressToFormAddressData(l):{country_id:l.countryId,region:l.region,region_id:l.regionId,postcode:l.postcode};j.set("shippingAddress",g.extend({},j.get("shippingAddress"),k));if(!b.isVirtual()){j.on("shippingAddress",function(o){h.setShippingAddressFromData(o)})}else{j.on("shippingAddress",function(o){h.setBillingAddressFromData(o)})}}}catch(n){(console.error||console.log).call(console,n.message||n)}})});return i}});