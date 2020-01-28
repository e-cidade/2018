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

//MODULO: caixa
//CLASSE DA ENTIDADE debcontaparam
class cl_debcontaparam { 
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
   var $d62_instituicao = 0; 
   var $d62_banco = 0; 
   var $d62_convenio = null; 
   var $d62_ultimonsa = 0; 
   var $d62_mascara = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d62_instituicao = int4 = codigo da instituicao 
                 d62_banco = int4 = codigo do banco 
                 d62_convenio = varchar(20) = Convenio 
                 d62_ultimonsa = int4 = Ultimo NSA 
                 d62_mascara = varchar(25) = Mascara 
                 ";
   //funcao construtor da classe 
   function cl_debcontaparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("debcontaparam"); 
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
       $this->d62_instituicao = ($this->d62_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["d62_instituicao"]:$this->d62_instituicao);
       $this->d62_banco = ($this->d62_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["d62_banco"]:$this->d62_banco);
       $this->d62_convenio = ($this->d62_convenio == ""?@$GLOBALS["HTTP_POST_VARS"]["d62_convenio"]:$this->d62_convenio);
       $this->d62_ultimonsa = ($this->d62_ultimonsa == ""?@$GLOBALS["HTTP_POST_VARS"]["d62_ultimonsa"]:$this->d62_ultimonsa);
       $this->d62_mascara = ($this->d62_mascara == ""?@$GLOBALS["HTTP_POST_VARS"]["d62_mascara"]:$this->d62_mascara);
     }else{
       $this->d62_instituicao = ($this->d62_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["d62_instituicao"]:$this->d62_instituicao);
       $this->d62_banco = ($this->d62_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["d62_banco"]:$this->d62_banco);
     }
   }
   // funcao para inclusao
   function incluir ($d62_instituicao,$d62_banco){ 
      $this->atualizacampos();
     if($this->d62_convenio == null ){ 
       $this->erro_sql = " Campo Convenio nao Informado.";
       $this->erro_campo = "d62_convenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d62_ultimonsa == null ){ 
       $this->erro_sql = " Campo Ultimo NSA nao Informado.";
       $this->erro_campo = "d62_ultimonsa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d62_mascara == null ){ 
       $this->erro_sql = " Campo Mascara nao Informado.";
       $this->erro_campo = "d62_mascara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->d62_instituicao = $d62_instituicao; 
       $this->d62_banco = $d62_banco; 
     if(($this->d62_instituicao == null) || ($this->d62_instituicao == "") ){ 
       $this->erro_sql = " Campo d62_instituicao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d62_banco == null) || ($this->d62_banco == "") ){ 
       $this->erro_sql = " Campo d62_banco nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into debcontaparam(
                                       d62_instituicao 
                                      ,d62_banco 
                                      ,d62_convenio 
                                      ,d62_ultimonsa 
                                      ,d62_mascara 
                       )
                values (
                                $this->d62_instituicao 
                               ,$this->d62_banco 
                               ,'$this->d62_convenio' 
                               ,$this->d62_ultimonsa 
                               ,'$this->d62_mascara' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros do debito com conta ($this->d62_instituicao."-".$this->d62_banco) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros do debito com conta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros do debito com conta ($this->d62_instituicao."-".$this->d62_banco) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d62_instituicao."-".$this->d62_banco;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d62_instituicao,$this->d62_banco));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7936,'$this->d62_instituicao','I')");
       $resac = db_query("insert into db_acountkey values($acount,7937,'$this->d62_banco','I')");
       $resac = db_query("insert into db_acount values($acount,1329,7936,'','".AddSlashes(pg_result($resaco,0,'d62_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1329,7937,'','".AddSlashes(pg_result($resaco,0,'d62_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1329,7938,'','".AddSlashes(pg_result($resaco,0,'d62_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1329,7939,'','".AddSlashes(pg_result($resaco,0,'d62_ultimonsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1329,7940,'','".AddSlashes(pg_result($resaco,0,'d62_mascara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d62_instituicao=null,$d62_banco=null) { 
      $this->atualizacampos();
     $sql = " update debcontaparam set ";
     $virgula = "";
     if(trim($this->d62_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d62_instituicao"])){ 
       $sql  .= $virgula." d62_instituicao = $this->d62_instituicao ";
       $virgula = ",";
       if(trim($this->d62_instituicao) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "d62_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d62_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d62_banco"])){ 
       $sql  .= $virgula." d62_banco = $this->d62_banco ";
       $virgula = ",";
       if(trim($this->d62_banco) == null ){ 
         $this->erro_sql = " Campo codigo do banco nao Informado.";
         $this->erro_campo = "d62_banco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d62_convenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d62_convenio"])){ 
       $sql  .= $virgula." d62_convenio = '$this->d62_convenio' ";
       $virgula = ",";
       if(trim($this->d62_convenio) == null ){ 
         $this->erro_sql = " Campo Convenio nao Informado.";
         $this->erro_campo = "d62_convenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d62_ultimonsa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d62_ultimonsa"])){ 
       $sql  .= $virgula." d62_ultimonsa = $this->d62_ultimonsa ";
       $virgula = ",";
       if(trim($this->d62_ultimonsa) == null ){ 
         $this->erro_sql = " Campo Ultimo NSA nao Informado.";
         $this->erro_campo = "d62_ultimonsa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d62_mascara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d62_mascara"])){ 
       $sql  .= $virgula." d62_mascara = '$this->d62_mascara' ";
       $virgula = ",";
       if(trim($this->d62_mascara) == null ){ 
         $this->erro_sql = " Campo Mascara nao Informado.";
         $this->erro_campo = "d62_mascara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d62_instituicao!=null){
       $sql .= " d62_instituicao = $this->d62_instituicao";
     }
     if($d62_banco!=null){
       $sql .= " and  d62_banco = $this->d62_banco";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d62_instituicao,$this->d62_banco));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7936,'$this->d62_instituicao','A')");
         $resac = db_query("insert into db_acountkey values($acount,7937,'$this->d62_banco','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d62_instituicao"]))
           $resac = db_query("insert into db_acount values($acount,1329,7936,'".AddSlashes(pg_result($resaco,$conresaco,'d62_instituicao'))."','$this->d62_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d62_banco"]))
           $resac = db_query("insert into db_acount values($acount,1329,7937,'".AddSlashes(pg_result($resaco,$conresaco,'d62_banco'))."','$this->d62_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d62_convenio"]))
           $resac = db_query("insert into db_acount values($acount,1329,7938,'".AddSlashes(pg_result($resaco,$conresaco,'d62_convenio'))."','$this->d62_convenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d62_ultimonsa"]))
           $resac = db_query("insert into db_acount values($acount,1329,7939,'".AddSlashes(pg_result($resaco,$conresaco,'d62_ultimonsa'))."','$this->d62_ultimonsa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d62_mascara"]))
           $resac = db_query("insert into db_acount values($acount,1329,7940,'".AddSlashes(pg_result($resaco,$conresaco,'d62_mascara'))."','$this->d62_mascara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do debito com conta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d62_instituicao."-".$this->d62_banco;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do debito com conta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d62_instituicao."-".$this->d62_banco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d62_instituicao."-".$this->d62_banco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d62_instituicao=null,$d62_banco=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d62_instituicao,$d62_banco));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7936,'$d62_instituicao','E')");
         $resac = db_query("insert into db_acountkey values($acount,7937,'$d62_banco','E')");
         $resac = db_query("insert into db_acount values($acount,1329,7936,'','".AddSlashes(pg_result($resaco,$iresaco,'d62_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1329,7937,'','".AddSlashes(pg_result($resaco,$iresaco,'d62_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1329,7938,'','".AddSlashes(pg_result($resaco,$iresaco,'d62_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1329,7939,'','".AddSlashes(pg_result($resaco,$iresaco,'d62_ultimonsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1329,7940,'','".AddSlashes(pg_result($resaco,$iresaco,'d62_mascara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from debcontaparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d62_instituicao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d62_instituicao = $d62_instituicao ";
        }
        if($d62_banco != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d62_banco = $d62_banco ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do debito com conta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d62_instituicao."-".$d62_banco;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do debito com conta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d62_instituicao."-".$d62_banco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d62_instituicao."-".$d62_banco;
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
        $this->erro_sql   = "Record Vazio na Tabela:debcontaparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d62_instituicao=null,$d62_banco=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaparam ";
     $sql .= "      inner join db_config  on  db_config.codigo = debcontaparam.d62_instituicao";
     $sql .= "      inner join bancos  on  bancos.codbco = debcontaparam.d62_banco";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($d62_instituicao!=null ){
         $sql2 .= " where debcontaparam.d62_instituicao = $d62_instituicao "; 
       } 
       if($d62_banco!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " debcontaparam.d62_banco = $d62_banco "; 
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
   function sql_query_file ( $d62_instituicao=null,$d62_banco=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($d62_instituicao!=null ){
         $sql2 .= " where debcontaparam.d62_instituicao = $d62_instituicao "; 
       } 
       if($d62_banco!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " debcontaparam.d62_banco = $d62_banco "; 
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