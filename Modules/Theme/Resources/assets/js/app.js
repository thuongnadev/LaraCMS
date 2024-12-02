import '../sass/app.scss';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import seo from '../js/seo';
import 'flowbite';

window.Swiper = Swiper;

document.addEventListener('DOMContentLoaded', () => {
    seo();
});