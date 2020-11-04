import moment from 'moment';

const now = moment();
const DateTime = () => now.format('YYYY-MM-DD hh:mm:ss');

export default DateTime;
