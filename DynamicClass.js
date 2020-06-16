import Slide17 from '../slide/Slide17';
import Slide16 from '../slide/Slide16';
import Slide15 from '../slide/Slide15';
import Slide14 from '../slide/Slide14';
import Slide11 from '../slide/Slide11';
import Slide10 from '../slide/Slide10';


// Use ES6 Object Literal Property Value Shorthand to maintain a map
// where the keys share the same names as the classes themselves
const classes = {
	Slide10,
	Slide11,
	Slide14,
	Slide15,
	Slide16,
	Slide17,
};

class DynamicClass {
    constructor (className) {
        return classes[className];
    }
}

export default DynamicClass;