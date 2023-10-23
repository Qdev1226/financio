import { render } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import DashboardPage from './dashboard-page';
import './dashboard.scss';

if ( document.getElementById('gutenverse-theme-dashboard') ) {
    domReady(() => {
        render(
            <DashboardPage/>,
            document.getElementById('gutenverse-theme-dashboard')
        );
    });
}