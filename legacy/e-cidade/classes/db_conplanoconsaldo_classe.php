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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanoconsaldo
class cl_conplanoconsaldo { 
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
   var $c59_anoexe = 0; 
   var $c59_codcon = 0; 
   var $c59_instit = 0; 
   var $c59_mes = 0; 
   var $c59_debito = 0; 
   var $c59_credito = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c59_anoexe = int4 = Exercicío 
                 c59_codcon = int4 = Código 
                 c59_instit = int4 = codigo da instituicao 
                 c59_mes = int4 = Mês 
                 c59_debito = float8 = Débito 
                 c59_credito = float8 = Crédito 
                 ";
   //funcao construtor da classe 
   function cl_conplanoconsaldo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoconsaldo"); 
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
       $this->c59_anoexe = ($this->c59_anoexe == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_anoexe"]:$this->c59_anoexe);
       $this->c59_codcon = ($this->c59_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_codcon"]:$this->c59_codcon);
       $this->c59_instit = ($this->c59_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_instit"]:$this->c59_instit);
       $this->c59_mes = ($this->c59_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_mes"]:$this->c59_mes);
       $this->c59_debito = ($this->c59_debito == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_debito"]:$this->c59_debito);
       $this->c59_credito = ($this->c59_credito == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_credito"]:$this->c59_credito);
     }else{
       $this->c59_anoexe = ($this->c59_anoexe == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_anoexe"]:$this->c59_anoexe);
       $this->c59_codcon = ($this->c59_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_codcon"]:$this->c59_codcon);
       $this->c59_instit = ($this->c59_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_instit"]:$this->c59_instit);
       $this->c59_mes = ($this->c59_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c59_mes"]:$this->c59_mes);
     }
   }
   // funcao para inclusao
   function incluir ($c59_anoexe,$c59_codcon,$c59_instit,$c59_mes){ 
      $this->atualizacampos();
     if($this->c59_debito == null ){ 
       $this->erro_sql = " Campo Débito nao Informado.";
       $this->erro_campo = "c59_debito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c59_credito == null ){ 
       $this->erro_sql = " Campo Crédito nao Informado.";
       $this->erro_campo = "c59_credito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c59_anoexe = $c59_anoexe; 
       $this->c59_codcon = $c59_codcon; 
       $this->c59_instit = $c59_instit; 
       $this->c59_mes = $c59_mes; 
     if(($this->c59_anoexe == null) || ($this->c59_anoexe == "") ){ 
       $this->erro_sql = " Campo c59_anoexe nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c59_codcon == null) || ($this->c59_codcon == "") ){ 
       $this->erro_sql = " Campo c59_codcon nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c59_instit == null) || ($this->c59_instit == "") ){ 
       $this->erro_sql = " Campo c59_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c59_mes == null) || ($this->c59_mes == "") ){ 
       $this->erro_sql = " Campo c59_mes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoconsaldo(
                                       c59_anoexe 
                                      ,c59_codcon 
                                      ,c59_instit 
                                      ,c59_mes 
                                      ,c59_debito 
                                      ,c59_credito 
                       )
                values (
                                $this->c59_anoexe 
                               ,$this->c59_codcon 
                               ,$this->c59_instit 
                               ,$this->c59_mes 
                               ,$this->c59_debito 
                               ,$this->c59_credito 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "conplanoconsaldo ($this->c59_anoexe."-".$this->c59_codcon."-".$this->c59_instit."-".$this->c59_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "conplanoconsaldo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "conplanoconsaldo ($this->c59_anoexe."-".$this->c59_codcon."-".$this->c59_instit."-".$this->c59_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c59_anoexe."-".$this->c59_codcon."-".$this->c59_instit."-".$this->c59_mes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c59_anoexe,$this->c59_codcon,$this->c59_instit,$this->c59_mes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6462,'$this->c59_anoexe','I')");
       $resac = db_query("insert into db_acountkey values($acount,6463,'$this->c59_codcon','I')");
       $resac = db_query("insert into db_acountkey values($acount,6464,'$this->c59_instit','I')");
       $resac = db_query("insert into db_acountkey values($acount,6465,'$this->c59_mes','I')");
       $resac = db_query("insert into db_acount values($acount,1062,6462,'','".AddSlashes(pg_result($resaco,0,'c59_anoexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1062,6463,'','".AddSlashes(pg_result($resaco,0,'c59_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1062,6464,'','".AddSlashes(pg_result($resaco,0,'c59_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1062,6465,'','".AddSlashes(pg_result($resaco,0,'c59_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1062,6466,'','".AddSlashes(pg_result($resaco,0,'c59_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1062,6467,'','".AddSlashes(pg_result($resaco,0,'c59_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c59_anoexe=null,$c59_codcon=null,$c59_instit=null,$c59_mes=null) { 
      $this->atualizacampos();
     $sql = " update conplanoconsaldo set ";
     $virgula = "";
     if(trim($this->c59_anoexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c59_anoexe"])){ 
       $sql  .= $virgula." c59_anoexe = $this->c59_anoexe ";
       $virgula = ",";
       if(trim($this->c59_anoexe) == null ){ 
         $this->erro_sql = " Campo Exercicío nao Informado.";
         $this->erro_campo = "c59_anoexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c59_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c59_codcon"])){ 
       $sql  .= $virgula." c59_codcon = $this->c59_codcon ";
       $virgula = ",";
       if(trim($this->c59_codcon) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "c59_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c59_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c59_instit"])){ 
       $sql  .= $virgula." c59_instit = $this->c59_instit ";
       $virgula = ",";
       if(trim($this->c59_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "c59_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c59_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c59_mes"])){ 
       $sql  .= $virgula." c59_mes = $this->c59_mes ";
       $virgula = ",";
       if(trim($this->c59_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "c59_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c59_debito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c59_debito"])){ 
       $sql  .= $virgula." c59_debito = $this->c59_debito ";
       $virgula = ",";
       if(trim($this->c59_debito) == null ){ 
         $this->erro_sql = " Campo Débito nao Informado.";
         $this->erro_campo = "c59_debito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c59_credito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c59_credito"])){ 
       $sql  .= $virgula." c59_credito = $this->c59_credito ";
       $virgula = ",";
       if(trim($this->c59_credito) == null ){ 
         $this->erro_sql = " Campo Crédito nao Informado.";
         $this->erro_campo = "c59_credito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c59_anoexe!=null){
       $sql .= " c59_anoexe = $this->c59_anoexe";
     }
     if($c59_codcon!=null){
       $sql .= " and  c59_codcon = $this->c59_codcon";
     }
     if($c59_instit!=null){
       $sql .= " and  c59_instit = $this->c59_instit";
     }
     if($c59_mes!=null){
       $sql .= " and  c59_mes = $this->c59_mes";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c59_anoexe,$this->c59_codcon,$this->c59_instit,$this->c59_mes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6462,'$this->c59_anoexe','A')");
         $resac = db_query("insert into db_acountkey values($acount,6463,'$this->c59_codcon','A')");
         $resac = db_query("insert into db_acountkey values($acount,6464,'$this->c59_instit','A')");
         $resac = db_query("insert into db_acountkey values($acount,6465,'$this->c59_mes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c59_anoexe"]))
           $resac = db_query("insert into db_acount values($acount,1062,6462,'".AddSlashes(pg_result($resaco,$conresaco,'c59_anoexe'))."','$this->c59_anoexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c59_codcon"]))
           $resac = db_query("insert into db_acount values($acount,1062,6463,'".AddSlashes(pg_result($resaco,$conresaco,'c59_codcon'))."','$this->c59_codcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c59_instit"]))
           $resac = db_query("insert into db_acount values($acount,1062,6464,'".AddSlashes(pg_result($resaco,$conresaco,'c59_instit'))."','$this->c59_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c59_mes"]))
           $resac = db_query("insert into db_acount values($acount,1062,6465,'".AddSlashes(pg_result($resaco,$conresaco,'c59_mes'))."','$this->c59_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c59_debito"]))
           $resac = db_query("insert into db_acount values($acount,1062,6466,'".AddSlashes(pg_result($resaco,$conresaco,'c59_debito'))."','$this->c59_debito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c59_credito"]))
           $resac = db_query("insert into db_acount values($acount,1062,6467,'".AddSlashes(pg_result($resaco,$conresaco,'c59_credito'))."','$this->c59_credito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conplanoconsaldo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c59_anoexe."-".$this->c59_codcon."-".$this->c59_instit."-".$this->c59_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "conplanoconsaldo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c59_anoexe."-".$this->c59_codcon."-".$this->c59_instit."-".$this->c59_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c59_anoexe."-".$this->c59_codcon."-".$this->c59_instit."-".$this->c59_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c59_anoexe=null,$c59_codcon=null,$c59_instit=null,$c59_mes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c59_anoexe,$c59_codcon,$c59_instit,$c59_mes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6462,'$c59_anoexe','E')");
         $resac = db_query("insert into db_acountkey values($acount,6463,'$c59_codcon','E')");
         $resac = db_query("insert into db_acountkey values($acount,6464,'$c59_instit','E')");
         $resac = db_query("insert into db_acountkey values($acount,6465,'$c59_mes','E')");
         $resac = db_query("insert into db_acount values($acount,1062,6462,'','".AddSlashes(pg_result($resaco,$iresaco,'c59_anoexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1062,6463,'','".AddSlashes(pg_result($resaco,$iresaco,'c59_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1062,6464,'','".AddSlashes(pg_result($resaco,$iresaco,'c59_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1062,6465,'','".AddSlashes(pg_result($resaco,$iresaco,'c59_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1062,6466,'','".AddSlashes(pg_result($resaco,$iresaco,'c59_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1062,6467,'','".AddSlashes(pg_result($resaco,$iresaco,'c59_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoconsaldo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c59_anoexe != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c59_anoexe = $c59_anoexe ";
        }
        if($c59_codcon != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c59_codcon = $c59_codcon ";
        }
        if($c59_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c59_instit = $c59_instit ";
        }
        if($c59_mes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c59_mes = $c59_mes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conplanoconsaldo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c59_anoexe."-".$c59_codcon."-".$c59_instit."-".$c59_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "conplanoconsaldo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c59_anoexe."-".$c59_codcon."-".$c59_instit."-".$c59_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c59_anoexe."-".$c59_codcon."-".$c59_instit."-".$c59_mes;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoconsaldo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c59_anoexe=null,$c59_codcon=null,$c59_instit=null,$c59_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conplanoconsaldo ";
     $sql .= "      inner join db_config  on  db_config.codigo = conplanoconsaldo.c59_instit";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = conplanoconsaldo.c59_codcon";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql2 = "";
     if($dbwhere==""){
       if($c59_anoexe!=null ){
         $sql2 .= " where conplanoconsaldo.c59_anoexe = $c59_anoexe "; 
       } 
       if($c59_codcon!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoconsaldo.c59_codcon = $c59_codcon "; 
       } 
       if($c59_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoconsaldo.c59_instit = $c59_instit "; 
       } 
       if($c59_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoconsaldo.c59_mes = $c59_mes "; 
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
   function sql_query_file ( $c59_anoexe=null,$c59_codcon=null,$c59_instit=null,$c59_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conplanoconsaldo ";
     $sql2 = "";
     if($dbwhere==""){
       if($c59_anoexe!=null ){
         $sql2 .= " where conplanoconsaldo.c59_anoexe = $c59_anoexe "; 
       } 
       if($c59_codcon!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoconsaldo.c59_codcon = $c59_codcon "; 
       } 
       if($c59_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoconsaldo.c59_instit = $c59_instit "; 
       } 
       if($c59_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoconsaldo.c59_mes = $c59_mes "; 
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