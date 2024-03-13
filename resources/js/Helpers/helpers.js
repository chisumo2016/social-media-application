
export  const isImage = (attachment) =>{

    let mime = attachment.mime || attachment.type
     mime = mime.split('/')
    return mime[0].toLowerCase() ==  'image' //Attachment Type is image
}

export  const isVideo = (attachment) =>{

    let mime = attachment.mime || attachment.type
    mime = mime.split('/')
    return mime[0].toLowerCase() ==  'video' //Attachment Type is image
}


// function isImage(attachment) {
//     const  mime = attachment.mime.split('/')
//     return mime[0].toLowerCase() ==  'image'
// }
