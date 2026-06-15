const { Client } = require('pg'); 
const client = new Client({ connectionString: 'postgresql://neondb_owner:npg_uUoPRrf8v9Hw@ep-rough-salad-aoa36q2a.c-2.ap-southeast-1.aws.neon.tech/neondb?sslmode=require' }); 
client.connect().then(() => client.query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")).then(res => { console.log(res.rows); client.end(); }).catch(e => console.error(e));
