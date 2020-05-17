`BaseConversionInterface` enables two methods: `convertToBase()` and `getBase()`, which do exactly what someone would expect them to.

!!! note "Base Conversion is Done Just-In-Time"
    Internally, the values of objects which implement the `BaseConversionInterface` always store the number in base-10, since this is the only base that arithmetic can actually be performed in by any of the associated extensions.
    
    Base conversion happens when a call is made to `getValue()`. Even on objects which have a base other than base-10, this can be avoided by calls to `getAsBaseTenNumber()` and `getAsBaseTenRealNumber()`.