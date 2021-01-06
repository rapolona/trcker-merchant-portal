const {DataTypes} = require("sequelize")

module.exports = (sequelize, Sequelize) => {
    const AdminDetail = sequelize.define("admin_detail", {
        admin_detail_id:{
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: Sequelize.UUIDV4
        },
        first_name:{
            type: Sequelize.STRING
        },
        last_name:{
            type: Sequelize.STRING
        },
        designation:{
            type: Sequelize.STRING
        },
        email_address:{
            type: Sequelize.STRING
        },
        contact_number:{
            type: Sequelize.STRING
        },
        profile_image:{
            type: Sequelize.TEXT('long')
        },
    });
    return AdminDetail
}