import { dropZone } from './helpers/helpers';

let allowTypes = ['jpg', 'jpeg', 'png','webp','gif'];
dropZone('.settings form.form .logo .dropzone #choose', allowTypes);
dropZone('.settings form.form .favicon .dropzone #choose', allowTypes);