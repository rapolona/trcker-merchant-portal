module.exports = {
  HOST: "localhost",
  USER: "tester",
  PASSWORD: "password",
  DB: "merchant-portal-git",
  dialect: "mysql",
  pool: {
    max: 5,
    min: 0,
    acquire: 30000,
    idle: 10000
  }
};