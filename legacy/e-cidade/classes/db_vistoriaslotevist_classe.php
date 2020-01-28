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

//MODULO: fiscal
//CLASSE DA ENTIDADE vistoriaslotevist
class cl_vistoriaslotevist { 
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
   var $y05_vistoriaslotevist = 0; 
   var $y05_vistoriaslote = 0; 
   var $y05_codvist = 0; 
   var $y05_codmsg = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y05_vistoriaslotevist = int8 = codigo da vistoria lancada 
                 y05_vistoriaslote = int8 = Codigo do lote das vistorias 
                 y05_codvist = int4 = Código da Vistoria 
                 y05_codmsg = int4 = codigo da mensagem 
                 ";
   //funcao construtor da classe 
   function cl_vistoriaslotevist() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vistoriaslotevist"); 
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
       $this->y05_vistoriaslotevist = ($this->y05_vistoriaslotevist == ""?@$GLOBALS["HTTP_POST_VARS"]["y05_vistoriaslotevist"]:$this->y05_vistoriaslotevist);
       $this->y05_vistoriaslote = ($this->y05_vistoriaslote == ""?@$GLOBALS["HTTP_POST_VARS"]["y05_vistoriaslote"]:$this->y05_vistoriaslote);
       $this->y05_codvist = ($this->y05_codvist == ""?@$GLOBALS["HTTP_POST_VARS"]["y05_codvist"]:$this->y05_codvist);
       $this->y05_codmsg = ($this->y05_codmsg == ""?@$GLOBALS["HTTP_POST_VARS"]["y05_codmsg"]:$this->y05_codmsg);
     }else{
       $this->y05_vistoriaslotevist = ($this->y05_vistoriaslotevist == ""?@$GLOBALS["HTTP_POST_VARS"]["y05_vistoriaslotevist"]:$this->y05_vistoriaslotevist);
       $this->y05_vistoriaslote = ($this->y05_vistoriaslote == ""?@$GLOBALS["HTTP_POST_VARS"]["y05_vistoriaslote"]:$this->y05_vistoriaslote);
     }
   }
   // funcao para inclusao
   function incluir ($y05_vistoriaslotevist){ 
      $this->atualizacampos();
     if($this->y05_codvist == null ){ 
       $this->erro_sql = " Campo Código da Vistoria nao Informado.";
       $this->erro_campo = "y05_codvist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y05_codmsg == null ){ 
       $this->erro_sql = " Campo codigo da mensagem nao Informado.";
       $this->erro_campo = "y05_codmsg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y05_vistoriaslotevist == "" || $y05_vistoriaslotevist == null ){
       $result = db_query("select nextval('vistoriaslotevist_y05_vistoriaslotevist_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vistoriaslotevist_y05_vistoriaslotevist_seq do campo: y05_vistoriaslotevist"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y05_vistoriaslotevist = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vistoriaslotevist_y05_vistoriaslotevist_seq");
       if(($result != false) && (pg_result($result,0,0) < $y05_vistoriaslotevist)){
         $this->erro_sql = " Campo y05_vistoriaslotevist maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y05_vistoriaslotevist = $y05_vistoriaslotevist; 
       }
     }
     if(($this->y05_vistoriaslotevist == null) || ($this->y05_vistoriaslotevist == "") ){ 
       $this->erro_sql = " Campo y05_vistoriaslotevist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vistoriaslotevist(
                                       y05_vistoriaslotevist 
                                      ,y05_vistoriaslote 
                                      ,y05_codvist 
                                      ,y05_codmsg 
                       )
                values (
                                $this->y05_vistoriaslotevist 
                               ,$this->y05_vistoriaslote 
                               ,$this->y05_codvist 
                               ,$this->y05_codmsg 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligação da da lotevist com a vistorias ($this->y05_vistoriaslotevist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligação da da lotevist com a vistorias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligação da da lotevist com a vistorias ($this->y05_vistoriaslotevist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y05_vistoriaslotevist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y05_vistoriaslotevist));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8343,'$this->y05_vistoriaslotevist','I')");
       $resac = db_query("insert into db_acount values($acount,1409,8343,'','".AddSlashes(pg_result($resaco,0,'y05_vistoriaslotevist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1409,8342,'','".AddSlashes(pg_result($resaco,0,'y05_vistoriaslote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1409,8344,'','".AddSlashes(pg_result($resaco,0,'y05_codvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1409,8371,'','".AddSlashes(pg_result($resaco,0,'y05_codmsg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y05_vistoriaslotevist=null) { 
      $this->atualizacampos();
     $sql = " update vistoriaslotevist set ";
     $virgula = "";
     if(trim($this->y05_vistoriaslotevist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y05_vistoriaslotevist"])){ 
       $sql  .= $virgula." y05_vistoriaslotevist = $this->y05_vistoriaslotevist ";
       $virgula = ",";
       if(trim($this->y05_vistoriaslotevist) == null ){ 
         $this->erro_sql = " Campo codigo da vistoria lancada nao Informado.";
         $this->erro_campo = "y05_vistoriaslotevist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y05_vistoriaslote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y05_vistoriaslote"])){ 
       $sql  .= $virgula." y05_vistoriaslote = $this->y05_vistoriaslote ";
       $virgula = ",";
       if(trim($this->y05_vistoriaslote) == null ){ 
         $this->erro_sql = " Campo Codigo do lote das vistorias nao Informado.";
         $this->erro_campo = "y05_vistoriaslote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y05_codvist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y05_codvist"])){ 
       $sql  .= $virgula." y05_codvist = $this->y05_codvist ";
       $virgula = ",";
       if(trim($this->y05_codvist) == null ){ 
         $this->erro_sql = " Campo Código da Vistoria nao Informado.";
         $this->erro_campo = "y05_codvist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y05_codmsg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y05_codmsg"])){ 
       $sql  .= $virgula." y05_codmsg = $this->y05_codmsg ";
       $virgula = ",";
       if(trim($this->y05_codmsg) == null ){ 
         $this->erro_sql = " Campo codigo da mensagem nao Informado.";
         $this->erro_campo = "y05_codmsg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y05_vistoriaslotevist!=null){
       $sql .= " y05_vistoriaslotevist = $this->y05_vistoriaslotevist";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y05_vistoriaslotevist));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8343,'$this->y05_vistoriaslotevist','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y05_vistoriaslotevist"]))
           $resac = db_query("insert into db_acount values($acount,1409,8343,'".AddSlashes(pg_result($resaco,$conresaco,'y05_vistoriaslotevist'))."','$this->y05_vistoriaslotevist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y05_vistoriaslote"]))
           $resac = db_query("insert into db_acount values($acount,1409,8342,'".AddSlashes(pg_result($resaco,$conresaco,'y05_vistoriaslote'))."','$this->y05_vistoriaslote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y05_codvist"]))
           $resac = db_query("insert into db_acount values($acount,1409,8344,'".AddSlashes(pg_result($resaco,$conresaco,'y05_codvist'))."','$this->y05_codvist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y05_codmsg"]))
           $resac = db_query("insert into db_acount values($acount,1409,8371,'".AddSlashes(pg_result($resaco,$conresaco,'y05_codmsg'))."','$this->y05_codmsg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação da da lotevist com a vistorias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y05_vistoriaslotevist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação da da lotevist com a vistorias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y05_vistoriaslotevist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y05_vistoriaslotevist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y05_vistoriaslotevist=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y05_vistoriaslotevist));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8343,'$y05_vistoriaslotevist','E')");
         $resac = db_query("insert into db_acount values($acount,1409,8343,'','".AddSlashes(pg_result($resaco,$iresaco,'y05_vistoriaslotevist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1409,8342,'','".AddSlashes(pg_result($resaco,$iresaco,'y05_vistoriaslote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1409,8344,'','".AddSlashes(pg_result($resaco,$iresaco,'y05_codvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1409,8371,'','".AddSlashes(pg_result($resaco,$iresaco,'y05_codmsg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vistoriaslotevist
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y05_vistoriaslotevist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y05_vistoriaslotevist = $y05_vistoriaslotevist ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação da da lotevist com a vistorias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y05_vistoriaslotevist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação da da lotevist com a vistorias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y05_vistoriaslotevist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y05_vistoriaslotevist;
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
        $this->erro_sql   = "Record Vazio na Tabela:vistoriaslotevist";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y05_vistoriaslotevist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistoriaslotevist ";
     $sql .= "      inner join vistorias  on  vistorias.y70_codvist = vistoriaslotevist.y05_codvist";
     $sql .= "      inner join vistoriaslote  on  vistoriaslote.y06_vistoriaslote = vistoriaslotevist.y05_vistoriaslote";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vistorias.y70_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vistorias.y70_coddepto";
     $sql .= "      inner join fandam  on  fandam.y39_codandam = vistorias.y70_ultandam";
     $sql .= "      inner join tipovistorias  on  tipovistorias.y77_codtipo = vistorias.y70_tipovist";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = vistoriaslote.y06_usuario";
     $sql .= "      inner join tipovistorias  as b on   b.y77_codtipo = vistoriaslote.y06_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($y05_vistoriaslotevist!=null ){
         $sql2 .= " where vistoriaslotevist.y05_vistoriaslotevist = $y05_vistoriaslotevist "; 
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
   function sql_query_file ( $y05_vistoriaslotevist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistoriaslotevist ";
     $sql2 = "";
     if($dbwhere==""){
       if($y05_vistoriaslotevist!=null ){
         $sql2 .= " where vistoriaslotevist.y05_vistoriaslotevist = $y05_vistoriaslotevist "; 
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