var nodemailer = require("nodemailer");

exports.sendMail = (to, from, subject, message, callback)=>{
    var transporter = nodemailer.createTransport({
        service: 'gmail',
        auth: {
            user: "dukewillard13@gmail.com",
            pass: '$3cur3Th1$'
        }
    });
    var mailOptions = {
        from: from,
        to: to,
        subject: subject,
        html: message
    };

    transporter.sendMail(mailOptions, (err, info)=>{
       if(err){
           console.log(err)
           res.status(500).send({message: "Error sending email"})
           return; 
       } 
       else{
           callback();
           console.log('Email Sent : ' + info.response)
       }
    })
}

