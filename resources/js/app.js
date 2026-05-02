import './bootstrap';

import Alpine from 'alpinejs';
import { registerDocumentRequestForm } from './document-request-form';

registerDocumentRequestForm(Alpine);

window.Alpine = Alpine;

Alpine.start();
