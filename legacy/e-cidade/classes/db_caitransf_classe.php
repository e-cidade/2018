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
//CLASSE DA ENTIDADE caitransf
class cl_caitransf { 
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
   var $k91_transf = 0; 
   var $k91_anousu = 0; 
   var $k91_instit = 0; 
   var $k91_descr = null; 
   var $k91_finalidade = null; 
   var $k91_debito = 0; 
   var $k91_credito = 0; 
   var $k91_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k91_transf = int4 = Trasferencia 
                 k91_anousu = int4 = Exercício 
                 k91_instit = int4 = Instituição 
                 k91_descr = varchar(50) = Descrição 
                 k91_finalidade = text = Finalidade 
                 k91_debito = int4 = Debito 
                 k91_credito = int4 = Credito 
                 k91_tipo = char(1) = Tipo da Transferência 
                 ";
   //funcao construtor da classe 
   function cl_caitransf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("caitransf"); 
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
       $this->k91_transf = ($this->k91_transf == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_transf"]:$this->k91_transf);
       $this->k91_anousu = ($this->k91_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_anousu"]:$this->k91_anousu);
       $this->k91_instit = ($this->k91_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_instit"]:$this->k91_instit);
       $this->k91_descr = ($this->k91_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_descr"]:$this->k91_descr);
       $this->k91_finalidade = ($this->k91_finalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_finalidade"]:$this->k91_finalidade);
       $this->k91_debito = ($this->k91_debito == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_debito"]:$this->k91_debito);
       $this->k91_credito = ($this->k91_credito == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_credito"]:$this->k91_credito);
       $this->k91_tipo = ($this->k91_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_tipo"]:$this->k91_tipo);
     }else{
       $this->k91_transf = ($this->k91_transf == ""?@$GLOBALS["HTTP_POST_VARS"]["k91_transf"]:$this->k91_transf);
     }
   }
   // funcao para inclusao
   function incluir ($k91_transf){ 
      $this->atualizacampos();
     if($this->k91_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "k91_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k91_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "k91_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k91_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "k91_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k91_finalidade == null ){ 
       $this->erro_sql = " Campo Finalidade nao Informado.";
       $this->erro_campo = "k91_finalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k91_debito == null ){ 
       $this->erro_sql = " Campo Debito nao Informado.";
       $this->erro_campo = "k91_debito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k91_credito == null ){ 
       $this->erro_sql = " Campo Credito nao Informado.";
       $this->erro_campo = "k91_credito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k91_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da Transferência nao Informado.";
       $this->erro_campo = "k91_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k91_transf == "" || $k91_transf == null ){
       $result = db_query("select nextval('caitransf_k91_transf_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: caitransf_k91_transf_seq do campo: k91_transf"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k91_transf = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from caitransf_k91_transf_seq");
       if(($result != false) && (pg_result($result,0,0) < $k91_transf)){
         $this->erro_sql = " Campo k91_transf maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k91_transf = $k91_transf; 
       }
     }
     if(($this->k91_transf == null) || ($this->k91_transf == "") ){ 
       $this->erro_sql = " Campo k91_transf nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into caitransf(
                                       k91_transf 
                                      ,k91_anousu 
                                      ,k91_instit 
                                      ,k91_descr 
                                      ,k91_finalidade 
                                      ,k91_debito 
                                      ,k91_credito 
                                      ,k91_tipo 
                       )
                values (
                                $this->k91_transf 
                               ,$this->k91_anousu 
                               ,$this->k91_instit 
                               ,'$this->k91_descr' 
                               ,'$this->k91_finalidade' 
                               ,$this->k91_debito 
                               ,$this->k91_credito 
                               ,'$this->k91_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k91_transf) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k91_transf) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k91_transf;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k91_transf));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8313,'$this->k91_transf','I')");
       $resac = db_query("insert into db_acount values($acount,1404,8313,'','".AddSlashes(pg_result($resaco,0,'k91_transf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1404,8314,'','".AddSlashes(pg_result($resaco,0,'k91_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1404,8315,'','".AddSlashes(pg_result($resaco,0,'k91_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1404,8316,'','".AddSlashes(pg_result($resaco,0,'k91_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1404,8317,'','".AddSlashes(pg_result($resaco,0,'k91_finalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1404,8318,'','".AddSlashes(pg_result($resaco,0,'k91_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1404,8319,'','".AddSlashes(pg_result($resaco,0,'k91_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1404,8800,'','".AddSlashes(pg_result($resaco,0,'k91_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k91_transf=null) { 
      $this->atualizacampos();
     $sql = " update caitransf set ";
     $virgula = "";
     if(trim($this->k91_transf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k91_transf"])){ 
       $sql  .= $virgula." k91_transf = $this->k91_transf ";
       $virgula = ",";
       if(trim($this->k91_transf) == null ){ 
         $this->erro_sql = " Campo Trasferencia nao Informado.";
         $this->erro_campo = "k91_transf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k91_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k91_anousu"])){ 
       $sql  .= $virgula." k91_anousu = $this->k91_anousu ";
       $virgula = ",";
       if(trim($this->k91_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "k91_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k91_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k91_instit"])){ 
       $sql  .= $virgula." k91_instit = $this->k91_instit ";
       $virgula = ",";
       if(trim($this->k91_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k91_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k91_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k91_descr"])){ 
       $sql  .= $virgula." k91_descr = '$this->k91_descr' ";
       $virgula = ",";
       if(trim($this->k91_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "k91_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k91_finalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k91_finalidade"])){ 
       $sql  .= $virgula." k91_finalidade = '$this->k91_finalidade' ";
       $virgula = ",";
       if(trim($this->k91_finalidade) == null ){ 
         $this->erro_sql = " Campo Finalidade nao Informado.";
         $this->erro_campo = "k91_finalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k91_debito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k91_debito"])){ 
       $sql  .= $virgula." k91_debito = $this->k91_debito ";
       $virgula = ",";
       if(trim($this->k91_debito) == null ){ 
         $this->erro_sql = " Campo Debito nao Informado.";
         $this->erro_campo = "k91_debito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k91_credito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k91_credito"])){ 
       $sql  .= $virgula." k91_credito = $this->k91_credito ";
       $virgula = ",";
       if(trim($this->k91_credito) == null ){ 
         $this->erro_sql = " Campo Credito nao Informado.";
         $this->erro_campo = "k91_credito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k91_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k91_tipo"])){ 
       $sql  .= $virgula." k91_tipo = '$this->k91_tipo' ";
       $virgula = ",";
       if(trim($this->k91_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da Transferência nao Informado.";
         $this->erro_campo = "k91_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k91_transf!=null){
       $sql .= " k91_transf = $this->k91_transf";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k91_transf));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8313,'$this->k91_transf','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k91_transf"]))
           $resac = db_query("insert into db_acount values($acount,1404,8313,'".AddSlashes(pg_result($resaco,$conresaco,'k91_transf'))."','$this->k91_transf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k91_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1404,8314,'".AddSlashes(pg_result($resaco,$conresaco,'k91_anousu'))."','$this->k91_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k91_instit"]))
           $resac = db_query("insert into db_acount values($acount,1404,8315,'".AddSlashes(pg_result($resaco,$conresaco,'k91_instit'))."','$this->k91_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k91_descr"]))
           $resac = db_query("insert into db_acount values($acount,1404,8316,'".AddSlashes(pg_result($resaco,$conresaco,'k91_descr'))."','$this->k91_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k91_finalidade"]))
           $resac = db_query("insert into db_acount values($acount,1404,8317,'".AddSlashes(pg_result($resaco,$conresaco,'k91_finalidade'))."','$this->k91_finalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k91_debito"]))
           $resac = db_query("insert into db_acount values($acount,1404,8318,'".AddSlashes(pg_result($resaco,$conresaco,'k91_debito'))."','$this->k91_debito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k91_credito"]))
           $resac = db_query("insert into db_acount values($acount,1404,8319,'".AddSlashes(pg_result($resaco,$conresaco,'k91_credito'))."','$this->k91_credito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k91_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1404,8800,'".AddSlashes(pg_result($resaco,$conresaco,'k91_tipo'))."','$this->k91_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k91_transf;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k91_transf;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k91_transf;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k91_transf=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k91_transf));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8313,'$k91_transf','E')");
         $resac = db_query("insert into db_acount values($acount,1404,8313,'','".AddSlashes(pg_result($resaco,$iresaco,'k91_transf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1404,8314,'','".AddSlashes(pg_result($resaco,$iresaco,'k91_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1404,8315,'','".AddSlashes(pg_result($resaco,$iresaco,'k91_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1404,8316,'','".AddSlashes(pg_result($resaco,$iresaco,'k91_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1404,8317,'','".AddSlashes(pg_result($resaco,$iresaco,'k91_finalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1404,8318,'','".AddSlashes(pg_result($resaco,$iresaco,'k91_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1404,8319,'','".AddSlashes(pg_result($resaco,$iresaco,'k91_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1404,8800,'','".AddSlashes(pg_result($resaco,$iresaco,'k91_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from caitransf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k91_transf != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k91_transf = $k91_transf ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k91_transf;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k91_transf;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k91_transf;
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
        $this->erro_sql   = "Record Vazio na Tabela:caitransf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k91_transf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransf ";
     $sql .= "      inner join db_config  on  db_config.codigo = caitransf.k91_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k91_transf!=null ){
         $sql2 .= " where caitransf.k91_transf = $k91_transf "; 
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
   function sql_query_descr( $k91_transf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransf ";
     $sql .= "      inner join db_config  on  db_config.codigo = caitransf.k91_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";

     $sql .= "      left join conplanoreduz r1 on r1.c61_reduz=k91_debito and r1.c61_anousu =k91_anousu ";
     $sql .= "      left join conplano cp1 on cp1.c60_codcon =r1.c61_codcon and cp1.c60_anousu =r1.c61_anousu  ";

     $sql .= "      left join conplanoreduz r2 on r2.c61_reduz=k91_credito and r2.c61_anousu =k91_anousu ";
     $sql .= "      left join conplano cp2 on cp2.c60_codcon =r2.c61_codcon and cp2.c60_anousu =r2.c61_anousu  ";

     $sql2 = "";
     if($dbwhere==""){
       if($k91_transf!=null ){
         $sql2 .= " where caitransf.k91_transf = $k91_transf "; 
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
   function sql_query_file ( $k91_transf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransf ";
     $sql2 = "";
     if($dbwhere==""){
       if($k91_transf!=null ){
         $sql2 .= " where caitransf.k91_transf = $k91_transf "; 
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