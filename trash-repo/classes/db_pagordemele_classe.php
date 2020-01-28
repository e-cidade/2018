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

//MODULO: empenho
//CLASSE DA ENTIDADE pagordemele
class cl_pagordemele { 
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
   var $e53_codord = 0; 
   var $e53_codele = 0; 
   var $e53_valor = 0; 
   var $e53_vlranu = 0; 
   var $e53_vlrpag = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e53_codord = int4 = Ordem 
                 e53_codele = int4 = Código Elemento 
                 e53_valor = float8 = Valor 
                 e53_vlranu = float8 = Anulado 
                 e53_vlrpag = float8 = Pago 
                 ";
   //funcao construtor da classe 
   function cl_pagordemele() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pagordemele"); 
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
       $this->e53_codord = ($this->e53_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e53_codord"]:$this->e53_codord);
       $this->e53_codele = ($this->e53_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["e53_codele"]:$this->e53_codele);
       $this->e53_valor = ($this->e53_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e53_valor"]:$this->e53_valor);
       $this->e53_vlranu = ($this->e53_vlranu == ""?@$GLOBALS["HTTP_POST_VARS"]["e53_vlranu"]:$this->e53_vlranu);
       $this->e53_vlrpag = ($this->e53_vlrpag == ""?@$GLOBALS["HTTP_POST_VARS"]["e53_vlrpag"]:$this->e53_vlrpag);
     }else{
       $this->e53_codord = ($this->e53_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e53_codord"]:$this->e53_codord);
       $this->e53_codele = ($this->e53_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["e53_codele"]:$this->e53_codele);
     }
   }
   // funcao para inclusao
   function incluir ($e53_codord,$e53_codele){ 
      $this->atualizacampos();
     if($this->e53_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "e53_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e53_vlranu == null ){ 
       $this->erro_sql = " Campo Anulado nao Informado.";
       $this->erro_campo = "e53_vlranu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e53_vlrpag == null ){ 
       $this->erro_sql = " Campo Pago nao Informado.";
       $this->erro_campo = "e53_vlrpag";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->e53_codord = $e53_codord; 
       $this->e53_codele = $e53_codele; 
     if(($this->e53_codord == null) || ($this->e53_codord == "") ){ 
       $this->erro_sql = " Campo e53_codord nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e53_codele == null) || ($this->e53_codele == "") ){ 
       $this->erro_sql = " Campo e53_codele nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pagordemele(
                                       e53_codord 
                                      ,e53_codele 
                                      ,e53_valor 
                                      ,e53_vlranu 
                                      ,e53_vlrpag 
                       )
                values (
                                $this->e53_codord 
                               ,$this->e53_codele 
                               ,$this->e53_valor 
                               ,$this->e53_vlranu 
                               ,$this->e53_vlrpag 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordem de pagamento  ($this->e53_codord."-".$this->e53_codele) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordem de pagamento  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordem de pagamento  ($this->e53_codord."-".$this->e53_codele) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e53_codord."-".$this->e53_codele;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e53_codord,$this->e53_codele));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5751,'$this->e53_codord','I')");
       $resac = db_query("insert into db_acountkey values($acount,5752,'$this->e53_codele','I')");
       $resac = db_query("insert into db_acount values($acount,911,5751,'','".AddSlashes(pg_result($resaco,0,'e53_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,911,5752,'','".AddSlashes(pg_result($resaco,0,'e53_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,911,5440,'','".AddSlashes(pg_result($resaco,0,'e53_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,911,5736,'','".AddSlashes(pg_result($resaco,0,'e53_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,911,5737,'','".AddSlashes(pg_result($resaco,0,'e53_vlrpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e53_codord=null,$e53_codele=null) { 
      $this->atualizacampos();
     $sql = " update pagordemele set ";
     $virgula = "";
     if(trim($this->e53_codord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e53_codord"])){ 
       $sql  .= $virgula." e53_codord = $this->e53_codord ";
       $virgula = ",";
       if(trim($this->e53_codord) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "e53_codord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e53_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e53_codele"])){ 
       $sql  .= $virgula." e53_codele = $this->e53_codele ";
       $virgula = ",";
       if(trim($this->e53_codele) == null ){ 
         $this->erro_sql = " Campo Código Elemento nao Informado.";
         $this->erro_campo = "e53_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e53_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e53_valor"])){ 
       $sql  .= $virgula." e53_valor = $this->e53_valor ";
       $virgula = ",";
       if(trim($this->e53_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "e53_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e53_vlranu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e53_vlranu"])){ 
       $sql  .= $virgula." e53_vlranu = $this->e53_vlranu ";
       $virgula = ",";
       if(trim($this->e53_vlranu) == null ){ 
         $this->erro_sql = " Campo Anulado nao Informado.";
         $this->erro_campo = "e53_vlranu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e53_vlrpag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e53_vlrpag"])){ 
       $sql  .= $virgula." e53_vlrpag = $this->e53_vlrpag ";
       $virgula = ",";
       if(trim($this->e53_vlrpag) == null ){ 
         $this->erro_sql = " Campo Pago nao Informado.";
         $this->erro_campo = "e53_vlrpag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e53_codord!=null){
       $sql .= " e53_codord = $this->e53_codord";
     }
     if($e53_codele!=null){
       $sql .= " and  e53_codele = $this->e53_codele";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e53_codord,$this->e53_codele));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5751,'$this->e53_codord','A')");
         $resac = db_query("insert into db_acountkey values($acount,5752,'$this->e53_codele','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e53_codord"]))
           $resac = db_query("insert into db_acount values($acount,911,5751,'".AddSlashes(pg_result($resaco,$conresaco,'e53_codord'))."','$this->e53_codord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e53_codele"]))
           $resac = db_query("insert into db_acount values($acount,911,5752,'".AddSlashes(pg_result($resaco,$conresaco,'e53_codele'))."','$this->e53_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e53_valor"]))
           $resac = db_query("insert into db_acount values($acount,911,5440,'".AddSlashes(pg_result($resaco,$conresaco,'e53_valor'))."','$this->e53_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e53_vlranu"]))
           $resac = db_query("insert into db_acount values($acount,911,5736,'".AddSlashes(pg_result($resaco,$conresaco,'e53_vlranu'))."','$this->e53_vlranu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e53_vlrpag"]))
           $resac = db_query("insert into db_acount values($acount,911,5737,'".AddSlashes(pg_result($resaco,$conresaco,'e53_vlrpag'))."','$this->e53_vlrpag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem de pagamento  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e53_codord."-".$this->e53_codele;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem de pagamento  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e53_codord."-".$this->e53_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e53_codord."-".$this->e53_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e53_codord=null,$e53_codele=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e53_codord,$e53_codele));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5751,'$e53_codord','E')");
         $resac = db_query("insert into db_acountkey values($acount,5752,'$e53_codele','E')");
         $resac = db_query("insert into db_acount values($acount,911,5751,'','".AddSlashes(pg_result($resaco,$iresaco,'e53_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,911,5752,'','".AddSlashes(pg_result($resaco,$iresaco,'e53_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,911,5440,'','".AddSlashes(pg_result($resaco,$iresaco,'e53_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,911,5736,'','".AddSlashes(pg_result($resaco,$iresaco,'e53_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,911,5737,'','".AddSlashes(pg_result($resaco,$iresaco,'e53_vlrpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pagordemele
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e53_codord != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e53_codord = $e53_codord ";
        }
        if($e53_codele != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e53_codele = $e53_codele ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem de pagamento  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e53_codord."-".$e53_codele;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem de pagamento  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e53_codord."-".$e53_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e53_codord."-".$e53_codele;
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
        $this->erro_sql   = "Record Vazio na Tabela:pagordemele";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e53_codord=null,$e53_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pagordemele ";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = pagordemele.e53_codord";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = pagordemele.e53_codele and orcelemento.o56_anousu = empempenho.e60_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($e53_codord!=null ){
         $sql2 .= " where pagordemele.e53_codord = $e53_codord "; 
       } 
       if($e53_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pagordemele.e53_codele = $e53_codele "; 
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
   function sql_query_file ( $e53_codord=null,$e53_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pagordemele ";
     $sql2 = "";
     if($dbwhere==""){
       if($e53_codord!=null ){
         $sql2 .= " where pagordemele.e53_codord = $e53_codord "; 
       } 
       if($e53_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pagordemele.e53_codele = $e53_codele "; 
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