module.exports = app => {
    const admins = require("../controllers/admin.controller.js");
    const admindetails = require("../controllers/admindetail.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js");
    const adminsessions = require("../controllers/adminsession.controller");

    var router = require("express").Router();

    router.post("/", admins.create);

    router.post("/login", admins.findAdminByCredential);

    router.get("/", adminMiddleware.isAuthenticated,admindetails.findOne);

    router.post("/logout", adminMiddleware.isAuthenticated, adminsessions.destroySession);

    app.use('/api/admins', router);
}