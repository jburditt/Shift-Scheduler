/* Copyright (c) 2007 the authors listed at the following URL, and/or
the authors of referenced articles or incorporated external code:
http://en.literateprograms.org/Quicksort_(JavaScript)?action=history&offset=20070102180347

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Retrieved from: http://en.literateprograms.org/Quicksort_(JavaScript)?oldid=8410
*/

Array.prototype.swap=function(a, b) {
	var tmp=this[a];
	this[a]=this[b];
	this[b]=tmp;
}

function partition(array, begin, end, pivot, compareProperty) {
	var piv=array[pivot];
	array.swap(pivot, end-1);
	var store=begin;
	var ix;
	for(ix=begin; ix<end-1; ++ix) {
		if(eval("array[ix]"+compareProperty+"<piv"+compareProperty)) {
			array.swap(store, ix);
			++store;
		}
		/*if (array[ix].jobID<piv.jobID) {
			array.swap(store, ix);
			++store;
		} else if (array[ix].id<=piv.id) {
			array.swap(store, ix);
			++store;
		}*/
	}
	array.swap(end-1, store);

	return store;
}

function qsort(array, begin, end, compareProperty) {
	if(end-1>begin) {
		var pivot=begin+Math.floor(Math.random()*(end-begin));

		pivot=partition(array, begin, end, pivot, compareProperty);

		qsort(array, begin, pivot, compareProperty);
		qsort(array, pivot+1, end, compareProperty);
	}
}

function quick_sort(array, compareProperty) {
	qsort(array, 0, array.length, compareProperty);
}