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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcindica
class cl_orcindica { 
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
   var $o10_indica = 0; 
   var $o10_descr = null; 
   var $o10_obs = null; 
   var $o10_periodicidade = 0; 
   var $o10_descrunidade = null; 
   var $o10_valorunidade = 0; 
   var $o10_valorindiceref = 0; 
   var $o10_descrindicefinal = null; 
   var $o10_valorindicefinal = 0; 
   var $o10_fonte = null; 
   var $o10_basegeografica = null; 
   var $o10_formulacalculo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o10_indica = int4 = Indicador 
                 o10_descr = varchar(40) = Descriçao 
                 o10_obs = text = Índice de Referência 
                 o10_periodicidade = int4 = Periodicidade 
                 o10_descrunidade = varchar(50) = Unidade de Medida 
                 o10_valorunidade = float4 = Unidade de Medida ( Valor ) 
                 o10_valorindiceref = float4 = Índice de Referência 
                 o10_descrindicefinal = text = Índice ao Final do Programa 
                 o10_valorindicefinal = float4 = Índice ao Final do Programa (Valor) 
                 o10_fonte = varchar(50) = Fonte 
                 o10_basegeografica = text = Base Geográfica 
                 o10_formulacalculo = text = Fórmula de Cálculo 
                 ";
   //funcao construtor da classe 
   function cl_orcindica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcindica"); 
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
       $this->o10_indica = ($this->o10_indica == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_indica"]:$this->o10_indica);
       $this->o10_descr = ($this->o10_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_descr"]:$this->o10_descr);
       $this->o10_obs = ($this->o10_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_obs"]:$this->o10_obs);
       $this->o10_periodicidade = ($this->o10_periodicidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_periodicidade"]:$this->o10_periodicidade);
       $this->o10_descrunidade = ($this->o10_descrunidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_descrunidade"]:$this->o10_descrunidade);
       $this->o10_valorunidade = ($this->o10_valorunidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_valorunidade"]:$this->o10_valorunidade);
       $this->o10_valorindiceref = ($this->o10_valorindiceref == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_valorindiceref"]:$this->o10_valorindiceref);
       $this->o10_descrindicefinal = ($this->o10_descrindicefinal == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_descrindicefinal"]:$this->o10_descrindicefinal);
       $this->o10_valorindicefinal = ($this->o10_valorindicefinal == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_valorindicefinal"]:$this->o10_valorindicefinal);
       $this->o10_fonte = ($this->o10_fonte == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_fonte"]:$this->o10_fonte);
       $this->o10_basegeografica = ($this->o10_basegeografica == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_basegeografica"]:$this->o10_basegeografica);
       $this->o10_formulacalculo = ($this->o10_formulacalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_formulacalculo"]:$this->o10_formulacalculo);
     }else{
       $this->o10_indica = ($this->o10_indica == ""?@$GLOBALS["HTTP_POST_VARS"]["o10_indica"]:$this->o10_indica);
     }
   }
   // funcao para inclusao
   function incluir ($o10_indica){ 
      $this->atualizacampos();
     if($this->o10_descr == null ){ 
       $this->erro_sql = " Campo Descriçao nao Informado.";
       $this->erro_campo = "o10_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o10_periodicidade == null ){ 
       $this->erro_sql = " Campo Periodicidade nao Informado.";
       $this->erro_campo = "o10_periodicidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o10_valorunidade == null ){ 
       $this->o10_valorunidade = "0";
     }
     if($this->o10_valorindiceref == null ){ 
       $this->o10_valorindiceref = "0";
     }
     if($this->o10_valorindicefinal == null ){ 
       $this->o10_valorindicefinal = "0";
     }
     if($o10_indica == "" || $o10_indica == null ){
       $result = db_query("select nextval('orcindica_o10_indica_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcindica_o10_indica_seq do campo: o10_indica"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o10_indica = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcindica_o10_indica_seq");
       if(($result != false) && (pg_result($result,0,0) < $o10_indica)){
         $this->erro_sql = " Campo o10_indica maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o10_indica = $o10_indica; 
       }
     }
     if(($this->o10_indica == null) || ($this->o10_indica == "") ){ 
       $this->erro_sql = " Campo o10_indica nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcindica(
                                       o10_indica 
                                      ,o10_descr 
                                      ,o10_obs 
                                      ,o10_periodicidade 
                                      ,o10_descrunidade 
                                      ,o10_valorunidade 
                                      ,o10_valorindiceref 
                                      ,o10_descrindicefinal 
                                      ,o10_valorindicefinal 
                                      ,o10_fonte 
                                      ,o10_basegeografica 
                                      ,o10_formulacalculo 
                       )
                values (
                                $this->o10_indica 
                               ,'$this->o10_descr' 
                               ,'$this->o10_obs' 
                               ,$this->o10_periodicidade 
                               ,'$this->o10_descrunidade' 
                               ,$this->o10_valorunidade 
                               ,$this->o10_valorindiceref 
                               ,'$this->o10_descrindicefinal' 
                               ,$this->o10_valorindicefinal 
                               ,'$this->o10_fonte' 
                               ,'$this->o10_basegeografica' 
                               ,'$this->o10_formulacalculo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Indicar de desempenho ($this->o10_indica) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Indicar de desempenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Indicar de desempenho ($this->o10_indica) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o10_indica;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o10_indica));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6859,'$this->o10_indica','I')");
       $resac = db_query("insert into db_acount values($acount,1125,6859,'','".AddSlashes(pg_result($resaco,0,'o10_indica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,6860,'','".AddSlashes(pg_result($resaco,0,'o10_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,6861,'','".AddSlashes(pg_result($resaco,0,'o10_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13660,'','".AddSlashes(pg_result($resaco,0,'o10_periodicidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13661,'','".AddSlashes(pg_result($resaco,0,'o10_descrunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13662,'','".AddSlashes(pg_result($resaco,0,'o10_valorunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13664,'','".AddSlashes(pg_result($resaco,0,'o10_valorindiceref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13667,'','".AddSlashes(pg_result($resaco,0,'o10_descrindicefinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13668,'','".AddSlashes(pg_result($resaco,0,'o10_valorindicefinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13669,'','".AddSlashes(pg_result($resaco,0,'o10_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13670,'','".AddSlashes(pg_result($resaco,0,'o10_basegeografica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1125,13671,'','".AddSlashes(pg_result($resaco,0,'o10_formulacalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o10_indica=null) { 
      $this->atualizacampos();
     $sql = " update orcindica set ";
     $virgula = "";
     if(trim($this->o10_indica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_indica"])){ 
       $sql  .= $virgula." o10_indica = $this->o10_indica ";
       $virgula = ",";
       if(trim($this->o10_indica) == null ){ 
         $this->erro_sql = " Campo Indicador nao Informado.";
         $this->erro_campo = "o10_indica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o10_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_descr"])){ 
       $sql  .= $virgula." o10_descr = '$this->o10_descr' ";
       $virgula = ",";
       if(trim($this->o10_descr) == null ){ 
         $this->erro_sql = " Campo Descriçao nao Informado.";
         $this->erro_campo = "o10_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o10_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_obs"])){ 
       $sql  .= $virgula." o10_obs = '$this->o10_obs' ";
       $virgula = ",";
     }
     if(trim($this->o10_periodicidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_periodicidade"])){ 
       $sql  .= $virgula." o10_periodicidade = $this->o10_periodicidade ";
       $virgula = ",";
       if(trim($this->o10_periodicidade) == null ){ 
         $this->erro_sql = " Campo Periodicidade nao Informado.";
         $this->erro_campo = "o10_periodicidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o10_descrunidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_descrunidade"])){ 
       $sql  .= $virgula." o10_descrunidade = '$this->o10_descrunidade' ";
       $virgula = ",";
     }
     if(trim($this->o10_valorunidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_valorunidade"])){ 
        if(trim($this->o10_valorunidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o10_valorunidade"])){ 
           $this->o10_valorunidade = "0" ; 
        } 
       $sql  .= $virgula." o10_valorunidade = $this->o10_valorunidade ";
       $virgula = ",";
     }
     if(trim($this->o10_valorindiceref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_valorindiceref"])){ 
        if(trim($this->o10_valorindiceref)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o10_valorindiceref"])){ 
           $this->o10_valorindiceref = "0" ; 
        } 
       $sql  .= $virgula." o10_valorindiceref = $this->o10_valorindiceref ";
       $virgula = ",";
     }
     if(trim($this->o10_descrindicefinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_descrindicefinal"])){ 
       $sql  .= $virgula." o10_descrindicefinal = '$this->o10_descrindicefinal' ";
       $virgula = ",";
     }
     if(trim($this->o10_valorindicefinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_valorindicefinal"])){ 
        if(trim($this->o10_valorindicefinal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o10_valorindicefinal"])){ 
           $this->o10_valorindicefinal = "0" ; 
        } 
       $sql  .= $virgula." o10_valorindicefinal = $this->o10_valorindicefinal ";
       $virgula = ",";
     }
     if(trim($this->o10_fonte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_fonte"])){ 
       $sql  .= $virgula." o10_fonte = '$this->o10_fonte' ";
       $virgula = ",";
     }
     if(trim($this->o10_basegeografica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_basegeografica"])){ 
       $sql  .= $virgula." o10_basegeografica = '$this->o10_basegeografica' ";
       $virgula = ",";
     }
     if(trim($this->o10_formulacalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o10_formulacalculo"])){ 
       $sql  .= $virgula." o10_formulacalculo = '$this->o10_formulacalculo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o10_indica!=null){
       $sql .= " o10_indica = $this->o10_indica";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o10_indica));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6859,'$this->o10_indica','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_indica"]))
           $resac = db_query("insert into db_acount values($acount,1125,6859,'".AddSlashes(pg_result($resaco,$conresaco,'o10_indica'))."','$this->o10_indica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_descr"]))
           $resac = db_query("insert into db_acount values($acount,1125,6860,'".AddSlashes(pg_result($resaco,$conresaco,'o10_descr'))."','$this->o10_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_obs"]))
           $resac = db_query("insert into db_acount values($acount,1125,6861,'".AddSlashes(pg_result($resaco,$conresaco,'o10_obs'))."','$this->o10_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_periodicidade"]))
           $resac = db_query("insert into db_acount values($acount,1125,13660,'".AddSlashes(pg_result($resaco,$conresaco,'o10_periodicidade'))."','$this->o10_periodicidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_descrunidade"]))
           $resac = db_query("insert into db_acount values($acount,1125,13661,'".AddSlashes(pg_result($resaco,$conresaco,'o10_descrunidade'))."','$this->o10_descrunidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_valorunidade"]))
           $resac = db_query("insert into db_acount values($acount,1125,13662,'".AddSlashes(pg_result($resaco,$conresaco,'o10_valorunidade'))."','$this->o10_valorunidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_valorindiceref"]))
           $resac = db_query("insert into db_acount values($acount,1125,13664,'".AddSlashes(pg_result($resaco,$conresaco,'o10_valorindiceref'))."','$this->o10_valorindiceref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_descrindicefinal"]))
           $resac = db_query("insert into db_acount values($acount,1125,13667,'".AddSlashes(pg_result($resaco,$conresaco,'o10_descrindicefinal'))."','$this->o10_descrindicefinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_valorindicefinal"]))
           $resac = db_query("insert into db_acount values($acount,1125,13668,'".AddSlashes(pg_result($resaco,$conresaco,'o10_valorindicefinal'))."','$this->o10_valorindicefinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_fonte"]))
           $resac = db_query("insert into db_acount values($acount,1125,13669,'".AddSlashes(pg_result($resaco,$conresaco,'o10_fonte'))."','$this->o10_fonte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_basegeografica"]))
           $resac = db_query("insert into db_acount values($acount,1125,13670,'".AddSlashes(pg_result($resaco,$conresaco,'o10_basegeografica'))."','$this->o10_basegeografica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o10_formulacalculo"]))
           $resac = db_query("insert into db_acount values($acount,1125,13671,'".AddSlashes(pg_result($resaco,$conresaco,'o10_formulacalculo'))."','$this->o10_formulacalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Indicar de desempenho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o10_indica;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Indicar de desempenho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o10_indica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o10_indica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o10_indica=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o10_indica));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6859,'$o10_indica','E')");
         $resac = db_query("insert into db_acount values($acount,1125,6859,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_indica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,6860,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,6861,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13660,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_periodicidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13661,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_descrunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13662,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_valorunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13664,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_valorindiceref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13667,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_descrindicefinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13668,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_valorindicefinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13669,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13670,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_basegeografica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1125,13671,'','".AddSlashes(pg_result($resaco,$iresaco,'o10_formulacalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcindica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o10_indica != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o10_indica = $o10_indica ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Indicar de desempenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o10_indica;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Indicar de desempenho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o10_indica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o10_indica;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcindica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o10_indica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcindica ";
     $sql .= "      inner join orcindicaperiodicidade  on  orcindicaperiodicidade.o09_sequencial = orcindica.o10_periodicidade";
     $sql2 = "";
     if($dbwhere==""){
       if($o10_indica!=null ){
         $sql2 .= " where orcindica.o10_indica = $o10_indica "; 
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
   // funcao do sql 
   function sql_query_file ( $o10_indica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcindica ";
     $sql2 = "";
     if($dbwhere==""){
       if($o10_indica!=null ){
         $sql2 .= " where orcindica.o10_indica = $o10_indica "; 
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