QUnit.test( "QUnit testing functionality test ;-) ", function( assert ) {
  assert.ok( true, "True succeeds" );
});

QUnit.test( "find test (find, log.js)", function( assert ) {
  var array = [3, "5", "proof", "test", 9999];
  assert.equal( find(array,1+2), true, "Find must find an element within the array" );
  assert.equal( find(array,"proof"), true, "Find must find an element within the array" );
  assert.equal( find(array,9999), true, "Find must find an element within the array" );
  assert.equal( find(array,5), true, "Find must find an element within the array" );
  assert.equal( find(array,"3"), true, "Find must find an element within the array" );
  assert.equal( find(array,8888), false, "Find must not find an element not present in the array" );
  assert.equal( find(array,0), false, "Find must not find an element not present in the array" );
  assert.equal( find(array,""), false, "Find must not find an element not present in the array" );  
  assert.equal( find(array,"test1"), false, "Find must not find an element not present in the array" );
  assert.equal( find(array,{}), false, "Find must not find an element not present in the array" );
});