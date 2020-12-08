module.exports = {
  HOST: "hustleph-rds-db.czotjo3wguec.ap-southeast-1.rds.amazonaws.com",
  PORT: 3306,
  USER: "huslteph_dbadmin",
  PASSWORD: "xaRdC85fqt",
  DB: "trckerdb",
  dialect: "mysql",
  pool: {
    max: 5,
    min: 0,
    acquire: 30000,
    idle: 10000
  }
};