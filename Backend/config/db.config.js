module.exports = {
  HOST: "localhost",
  USER: "tester",
  PASSWORD: "password",
  DB: "task-redesign",
  dialect: "mysql",
  pool: {
    max: 5,
    min: 0,
    acquire: 30000,
    idle: 10000
  }
};