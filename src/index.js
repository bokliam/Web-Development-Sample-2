import './_noir.scss';
import {Login} from './Login';
import {Stars} from './Stars';
import {MovieInfo} from './MovieInfo';
import $ from 'jquery';

$(document).ready(function() {
    new Login("#login");
    new Stars('#stars');
    new MovieInfo('#info');

});