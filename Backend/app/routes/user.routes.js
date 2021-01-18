module.exports = app => {
    const users = require("../controllers/user.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js");

    var router = require("express").Router();

    router.get("/users/listall", adminMiddleware.isAuthenticated, users.listUsers);
    router.get("/users/:user_id", adminMiddleware.isAuthenticated, users.getUserDetails)


    app.use('/merchant/', router);
}