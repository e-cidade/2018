<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: educação
//CLASSE DA ENTIDADE msgaviso
class cl_msgaviso { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $ed90_i_codigo = 0; 
   var $ed90_c_arquivo = null; 
   var $ed90_c_tabela = null; 
   var $ed90_t_msg = null; 
   var $ed90_c_arqdestino = null; 
   var $ed90_c_descrlink = null; 
   var $ed90_c_titulolink = null; 
   var $ed90_c_modulo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed90_i_codigo = int8 = Código 
                 ed90_c_arquivo = char(40) = Referência 
                 ed90_c_tabela = char(30) = Tabela Referente 
                 ed90_t_msg = text = Mensagem de Aviso 
                 ed90_c_arqdestino = char(40) = Arquivo do Link 
                 ed90_c_descrlink = char(40) = Descrição do Link 
                 ed90_c_titulolink = char(40) = Título do Link 
                 ed90_c_modulo = char(40) = Módulo 
                 ";
   //funcao construtor da classe 
   function cl_msgaviso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("msgaviso"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->ed90_i_codigo = ($this->ed90_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_i_codigo"]:$this->ed90_i_codigo);
       $this->ed90_c_arquivo = ($this->ed90_c_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_c_arquivo"]:$this->ed90_c_arquivo);
       $this->ed90_c_tabela = ($this->ed90_c_tabela == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_c_tabela"]:$this->ed90_c_tabela);
       $this->ed90_t_msg = ($this->ed90_t_msg == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_t_msg"]:$this->ed90_t_msg);
       $this->ed90_c_arqdestino = ($this->ed90_c_arqdestino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_c_arqdestino"]:$this->ed90_c_arqdestino);
       $this->ed90_c_descrlink = ($this->ed90_c_descrlink == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_c_descrlink"]:$this->ed90_c_descrlink);
       $this->ed90_c_titulolink = ($this->ed90_c_titulolink == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_c_titulolink"]:$this->ed90_c_titulolink);
       $this->ed90_c_modulo = ($this->ed90_c_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_c_modulo"]:$this->ed90_c_modulo);
     }else{
       $this->ed90_i_codigo = ($this->ed90_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed90_i_codigo"]:$this->ed90_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed90_i_codigo){ 
      $this->atualizacampos();
     if($this->ed90_c_tabela == null ){ 
       $this->erro_sql = " Campo Tabela Referente nao Informado.";
       $this->erro_campo = "ed90_c_tabela";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed90_t_msg == null ){ 
       $this->erro_sql = " Campo Mensagem de Aviso nao Informado.";
       $this->erro_campo = "ed90_t_msg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed90_c_arqdestino == null ){ 
       $this->erro_sql = " Campo Arquivo do Link nao Informado.";
       $this->erro_campo = "ed90_c_arqdestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed90_c_descrlink == null ){ 
       $this->erro_sql = " Campo Descrição do Link nao Informado.";
       $this->erro_campo = "ed90_c_descrlink";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed90_c_titulolink == null ){ 
       $this->erro_sql = " Campo Título do Link nao Informado.";
       $this->erro_campo = "ed90_c_titulolink";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed90_c_modulo == null ){ 
       $this->erro_sql = " Campo Módulo nao Informado.";
       $this->erro_campo = "ed90_c_modulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed90_i_codigo == "" || $ed90_i_codigo == null ){
       $result = db_query("select nextval('msgaviso_ed90_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: msgaviso_ed90_i_codigo_seq do campo: ed90_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed90_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from msgaviso_ed90_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed90_i_codigo)){
         $this->erro_sql = " Campo ed90_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed90_i_codigo = $ed90_i_codigo; 
       }
     }
     if(($this->ed90_i_codigo == null) || ($this->ed90_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed90_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into msgaviso(
                                       ed90_i_codigo 
                                      ,ed90_c_arquivo 
                                      ,ed90_c_tabela 
                                      ,ed90_t_msg 
                                      ,ed90_c_arqdestino 
                                      ,ed90_c_descrlink 
                                      ,ed90_c_titulolink 
                                      ,ed90_c_modulo 
                       )
                values (
                                $this->ed90_i_codigo 
                               ,'$this->ed90_c_arquivo' 
                               ,'$this->ed90_c_tabela' 
                               ,'$this->ed90_t_msg' 
                               ,'$this->ed90_c_arqdestino' 
                               ,'$this->ed90_c_descrlink' 
                               ,'$this->ed90_c_titulolink' 
                               ,'$this->ed90_c_modulo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Mensagem de Aviso ($this->ed90_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Mensagem de Aviso já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Mensagem de Aviso ($this->ed90_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed90_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed90_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008413,'$this->ed90_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010070,1008413,'','".AddSlashes(pg_result($resaco,0,'ed90_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010070,1008415,'','".AddSlashes(pg_result($resaco,0,'ed90_c_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010070,1008419,'','".AddSlashes(pg_result($resaco,0,'ed90_c_tabela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010070,1008414,'','".AddSlashes(pg_result($resaco,0,'ed90_t_msg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010070,1008416,'','".AddSlashes(pg_result($resaco,0,'ed90_c_arqdestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010070,1008417,'','".AddSlashes(pg_result($resaco,0,'ed90_c_descrlink'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010070,1008418,'','".AddSlashes(pg_result($resaco,0,'ed90_c_titulolink'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010070,1008939,'','".AddSlashes(pg_result($resaco,0,'ed90_c_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed90_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update msgaviso set ";
     $virgula = "";
     if(trim($this->ed90_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed90_i_codigo"])){ 
       $sql  .= $virgula." ed90_i_codigo = $this->ed90_i_codigo ";
       $virgula = ",";
       if(trim($this->ed90_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed90_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed90_c_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_arquivo"])){ 
       $sql  .= $virgula." ed90_c_arquivo = '$this->ed90_c_arquivo' ";
       $virgula = ",";
     }
     if(trim($this->ed90_c_tabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_tabela"])){ 
       $sql  .= $virgula." ed90_c_tabela = '$this->ed90_c_tabela' ";
       $virgula = ",";
       if(trim($this->ed90_c_tabela) == null ){ 
         $this->erro_sql = " Campo Tabela Referente nao Informado.";
         $this->erro_campo = "ed90_c_tabela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed90_t_msg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed90_t_msg"])){ 
       $sql  .= $virgula." ed90_t_msg = '$this->ed90_t_msg' ";
       $virgula = ",";
       if(trim($this->ed90_t_msg) == null ){ 
         $this->erro_sql = " Campo Mensagem de Aviso nao Informado.";
         $this->erro_campo = "ed90_t_msg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed90_c_arqdestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_arqdestino"])){ 
       $sql  .= $virgula." ed90_c_arqdestino = '$this->ed90_c_arqdestino' ";
       $virgula = ",";
       if(trim($this->ed90_c_arqdestino) == null ){ 
         $this->erro_sql = " Campo Arquivo do Link nao Informado.";
         $this->erro_campo = "ed90_c_arqdestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed90_c_descrlink)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_descrlink"])){ 
       $sql  .= $virgula." ed90_c_descrlink = '$this->ed90_c_descrlink' ";
       $virgula = ",";
       if(trim($this->ed90_c_descrlink) == null ){ 
         $this->erro_sql = " Campo Descrição do Link nao Informado.";
         $this->erro_campo = "ed90_c_descrlink";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed90_c_titulolink)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_titulolink"])){ 
       $sql  .= $virgula." ed90_c_titulolink = '$this->ed90_c_titulolink' ";
       $virgula = ",";
       if(trim($this->ed90_c_titulolink) == null ){ 
         $this->erro_sql = " Campo Título do Link nao Informado.";
         $this->erro_campo = "ed90_c_titulolink";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed90_c_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_modulo"])){ 
       $sql  .= $virgula." ed90_c_modulo = '$this->ed90_c_modulo' ";
       $virgula = ",";
       if(trim($this->ed90_c_modulo) == null ){ 
         $this->erro_sql = " Campo Módulo nao Informado.";
         $this->erro_campo = "ed90_c_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed90_i_codigo!=null){
       $sql .= " ed90_i_codigo = $this->ed90_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed90_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008413,'$this->ed90_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed90_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010070,1008413,'".AddSlashes(pg_result($resaco,$conresaco,'ed90_i_codigo'))."','$this->ed90_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_arquivo"]))
           $resac = db_query("insert into db_acount values($acount,1010070,1008415,'".AddSlashes(pg_result($resaco,$conresaco,'ed90_c_arquivo'))."','$this->ed90_c_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_tabela"]))
           $resac = db_query("insert into db_acount values($acount,1010070,1008419,'".AddSlashes(pg_result($resaco,$conresaco,'ed90_c_tabela'))."','$this->ed90_c_tabela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed90_t_msg"]))
           $resac = db_query("insert into db_acount values($acount,1010070,1008414,'".AddSlashes(pg_result($resaco,$conresaco,'ed90_t_msg'))."','$this->ed90_t_msg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_arqdestino"]))
           $resac = db_query("insert into db_acount values($acount,1010070,1008416,'".AddSlashes(pg_result($resaco,$conresaco,'ed90_c_arqdestino'))."','$this->ed90_c_arqdestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_descrlink"]))
           $resac = db_query("insert into db_acount values($acount,1010070,1008417,'".AddSlashes(pg_result($resaco,$conresaco,'ed90_c_descrlink'))."','$this->ed90_c_descrlink',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_titulolink"]))
           $resac = db_query("insert into db_acount values($acount,1010070,1008418,'".AddSlashes(pg_result($resaco,$conresaco,'ed90_c_titulolink'))."','$this->ed90_c_titulolink',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed90_c_modulo"]))
           $resac = db_query("insert into db_acount values($acount,1010070,1008939,'".AddSlashes(pg_result($resaco,$conresaco,'ed90_c_modulo'))."','$this->ed90_c_modulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Mensagem de Aviso nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed90_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Mensagem de Aviso nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed90_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed90_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed90_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed90_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008413,'$ed90_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010070,1008413,'','".AddSlashes(pg_result($resaco,$iresaco,'ed90_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010070,1008415,'','".AddSlashes(pg_result($resaco,$iresaco,'ed90_c_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010070,1008419,'','".AddSlashes(pg_result($resaco,$iresaco,'ed90_c_tabela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010070,1008414,'','".AddSlashes(pg_result($resaco,$iresaco,'ed90_t_msg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010070,1008416,'','".AddSlashes(pg_result($resaco,$iresaco,'ed90_c_arqdestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010070,1008417,'','".AddSlashes(pg_result($resaco,$iresaco,'ed90_c_descrlink'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010070,1008418,'','".AddSlashes(pg_result($resaco,$iresaco,'ed90_c_titulolink'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010070,1008939,'','".AddSlashes(pg_result($resaco,$iresaco,'ed90_c_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from msgaviso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed90_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed90_i_codigo = $ed90_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Mensagem de Aviso nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed90_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Mensagem de Aviso nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed90_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed90_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:msgaviso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed90_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from msgaviso ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed90_i_codigo!=null ){
         $sql2 .= " where msgaviso.ed90_i_codigo = $ed90_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $ed90_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from msgaviso ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed90_i_codigo!=null ){
         $sql2 .= " where msgaviso.ed90_i_codigo = $ed90_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>