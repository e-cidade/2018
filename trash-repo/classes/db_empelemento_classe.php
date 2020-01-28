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
//CLASSE DA ENTIDADE empelemento
class cl_empelemento { 
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
   var $e64_numemp = 0; 
   var $e64_codele = 0; 
   var $e64_vlremp = 0; 
   var $e64_vlrliq = 0; 
   var $e64_vlranu = 0; 
   var $e64_vlrpag = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e64_numemp = int4 = Número 
                 e64_codele = int4 = Código Elemento 
                 e64_vlremp = float8 = Vlr.Emp 
                 e64_vlrliq = float8 = Liquidado 
                 e64_vlranu = float8 = Anulado 
                 e64_vlrpag = float8 = Valor Pago 
                 ";
   //funcao construtor da classe 
   function cl_empelemento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empelemento"); 
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
       $this->e64_numemp = ($this->e64_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e64_numemp"]:$this->e64_numemp);
       $this->e64_codele = ($this->e64_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["e64_codele"]:$this->e64_codele);
       $this->e64_vlremp = ($this->e64_vlremp == ""?@$GLOBALS["HTTP_POST_VARS"]["e64_vlremp"]:$this->e64_vlremp);
       $this->e64_vlrliq = ($this->e64_vlrliq == ""?@$GLOBALS["HTTP_POST_VARS"]["e64_vlrliq"]:$this->e64_vlrliq);
       $this->e64_vlranu = ($this->e64_vlranu == ""?@$GLOBALS["HTTP_POST_VARS"]["e64_vlranu"]:$this->e64_vlranu);
       $this->e64_vlrpag = ($this->e64_vlrpag == ""?@$GLOBALS["HTTP_POST_VARS"]["e64_vlrpag"]:$this->e64_vlrpag);
     }else{
       $this->e64_numemp = ($this->e64_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e64_numemp"]:$this->e64_numemp);
       $this->e64_codele = ($this->e64_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["e64_codele"]:$this->e64_codele);
     }
   }
   // funcao para inclusao
   function incluir ($e64_numemp,$e64_codele){ 
      $this->atualizacampos();
     if($this->e64_vlremp == null ){ 
       $this->erro_sql = " Campo Vlr.Emp nao Informado.";
       $this->erro_campo = "e64_vlremp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e64_vlrliq == null ){ 
       $this->erro_sql = " Campo Liquidado nao Informado.";
       $this->erro_campo = "e64_vlrliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e64_vlranu == null ){ 
       $this->erro_sql = " Campo Anulado nao Informado.";
       $this->erro_campo = "e64_vlranu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e64_vlrpag == null ){ 
       $this->erro_sql = " Campo Valor Pago nao Informado.";
       $this->erro_campo = "e64_vlrpag";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->e64_numemp = $e64_numemp; 
       $this->e64_codele = $e64_codele; 
     if(($this->e64_numemp == null) || ($this->e64_numemp == "") ){ 
       $this->erro_sql = " Campo e64_numemp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e64_codele == null) || ($this->e64_codele == "") ){ 
       $this->erro_sql = " Campo e64_codele nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empelemento(
                                       e64_numemp 
                                      ,e64_codele 
                                      ,e64_vlremp 
                                      ,e64_vlrliq 
                                      ,e64_vlranu 
                                      ,e64_vlrpag 
                       )
                values (
                                $this->e64_numemp 
                               ,$this->e64_codele 
                               ,$this->e64_vlremp 
                               ,$this->e64_vlrliq 
                               ,$this->e64_vlranu 
                               ,$this->e64_vlrpag 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empenho com elementos ($this->e64_numemp."-".$this->e64_codele) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empenho com elementos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empenho com elementos ($this->e64_numemp."-".$this->e64_codele) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e64_numemp."-".$this->e64_codele;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e64_numemp,$this->e64_codele));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5718,'$this->e64_numemp','I')");
       $resac = db_query("insert into db_acountkey values($acount,5719,'$this->e64_codele','I')");
       $resac = db_query("insert into db_acount values($acount,905,5718,'','".AddSlashes(pg_result($resaco,0,'e64_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,905,5719,'','".AddSlashes(pg_result($resaco,0,'e64_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,905,5720,'','".AddSlashes(pg_result($resaco,0,'e64_vlremp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,905,5721,'','".AddSlashes(pg_result($resaco,0,'e64_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,905,5722,'','".AddSlashes(pg_result($resaco,0,'e64_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,905,5723,'','".AddSlashes(pg_result($resaco,0,'e64_vlrpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e64_numemp=null,$e64_codele=null) { 
      $this->atualizacampos();
     $sql = " update empelemento set ";
     $virgula = "";
     if(trim($this->e64_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e64_numemp"])){ 
       $sql  .= $virgula." e64_numemp = $this->e64_numemp ";
       $virgula = ",";
       if(trim($this->e64_numemp) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "e64_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e64_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e64_codele"])){ 
       $sql  .= $virgula." e64_codele = $this->e64_codele ";
       $virgula = ",";
       if(trim($this->e64_codele) == null ){ 
         $this->erro_sql = " Campo Código Elemento nao Informado.";
         $this->erro_campo = "e64_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e64_vlremp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e64_vlremp"])){ 
       $sql  .= $virgula." e64_vlremp = $this->e64_vlremp ";
       $virgula = ",";
       if(trim($this->e64_vlremp) == null ){ 
         $this->erro_sql = " Campo Vlr.Emp nao Informado.";
         $this->erro_campo = "e64_vlremp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e64_vlrliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e64_vlrliq"])){ 
       $sql  .= $virgula." e64_vlrliq = $this->e64_vlrliq ";
       $virgula = ",";
       if(trim($this->e64_vlrliq) == null ){ 
         $this->erro_sql = " Campo Liquidado nao Informado.";
         $this->erro_campo = "e64_vlrliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e64_vlranu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e64_vlranu"])){ 
       $sql  .= $virgula." e64_vlranu = $this->e64_vlranu ";
       $virgula = ",";
       if(trim($this->e64_vlranu) == null ){ 
         $this->erro_sql = " Campo Anulado nao Informado.";
         $this->erro_campo = "e64_vlranu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e64_vlrpag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e64_vlrpag"])){ 
       $sql  .= $virgula." e64_vlrpag = $this->e64_vlrpag ";
       $virgula = ",";
       if(trim($this->e64_vlrpag) == null ){ 
         $this->erro_sql = " Campo Valor Pago nao Informado.";
         $this->erro_campo = "e64_vlrpag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e64_numemp!=null){
       $sql .= " e64_numemp = $this->e64_numemp";
     }
     if($e64_codele!=null){
       $sql .= " and  e64_codele = $this->e64_codele";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e64_numemp,$this->e64_codele));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5718,'$this->e64_numemp','A')");
         $resac = db_query("insert into db_acountkey values($acount,5719,'$this->e64_codele','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e64_numemp"]))
           $resac = db_query("insert into db_acount values($acount,905,5718,'".AddSlashes(pg_result($resaco,$conresaco,'e64_numemp'))."','$this->e64_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e64_codele"]))
           $resac = db_query("insert into db_acount values($acount,905,5719,'".AddSlashes(pg_result($resaco,$conresaco,'e64_codele'))."','$this->e64_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e64_vlremp"]))
           $resac = db_query("insert into db_acount values($acount,905,5720,'".AddSlashes(pg_result($resaco,$conresaco,'e64_vlremp'))."','$this->e64_vlremp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e64_vlrliq"]))
           $resac = db_query("insert into db_acount values($acount,905,5721,'".AddSlashes(pg_result($resaco,$conresaco,'e64_vlrliq'))."','$this->e64_vlrliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e64_vlranu"]))
           $resac = db_query("insert into db_acount values($acount,905,5722,'".AddSlashes(pg_result($resaco,$conresaco,'e64_vlranu'))."','$this->e64_vlranu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e64_vlrpag"]))
           $resac = db_query("insert into db_acount values($acount,905,5723,'".AddSlashes(pg_result($resaco,$conresaco,'e64_vlrpag'))."','$this->e64_vlrpag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenho com elementos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e64_numemp."-".$this->e64_codele;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenho com elementos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e64_numemp."-".$this->e64_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e64_numemp."-".$this->e64_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e64_numemp=null,$e64_codele=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e64_numemp,$e64_codele));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5718,'$e64_numemp','E')");
         $resac = db_query("insert into db_acountkey values($acount,5719,'$e64_codele','E')");
         $resac = db_query("insert into db_acount values($acount,905,5718,'','".AddSlashes(pg_result($resaco,$iresaco,'e64_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,905,5719,'','".AddSlashes(pg_result($resaco,$iresaco,'e64_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,905,5720,'','".AddSlashes(pg_result($resaco,$iresaco,'e64_vlremp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,905,5721,'','".AddSlashes(pg_result($resaco,$iresaco,'e64_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,905,5722,'','".AddSlashes(pg_result($resaco,$iresaco,'e64_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,905,5723,'','".AddSlashes(pg_result($resaco,$iresaco,'e64_vlrpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empelemento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e64_numemp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e64_numemp = $e64_numemp ";
        }
        if($e64_codele != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e64_codele = $e64_codele ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenho com elementos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e64_numemp."-".$e64_codele;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenho com elementos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e64_numemp."-".$e64_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e64_numemp."-".$e64_codele;
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
        $this->erro_sql   = "Record Vazio na Tabela:empelemento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e64_numemp=null,$e64_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empelemento ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empelemento.e64_numemp";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empelemento.e64_codele and orcelemento.o56_anousu = empempenho.e60_anousu ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($e64_numemp!=null ){
         $sql2 .= " where empelemento.e64_numemp = $e64_numemp "; 
       } 
       if($e64_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empelemento.e64_codele = $e64_codele "; 
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
   function sql_query_file ( $e64_numemp=null,$e64_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empelemento ";
     $sql2 = "";
     if($dbwhere==""){
       if($e64_numemp!=null ){
         $sql2 .= " where empelemento.e64_numemp = $e64_numemp "; 
       } 
       if($e64_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empelemento.e64_codele = $e64_codele "; 
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