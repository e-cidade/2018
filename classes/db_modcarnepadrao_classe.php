<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: Caixa
//CLASSE DA ENTIDADE modcarnepadrao
class cl_modcarnepadrao { 
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
   var $k48_sequencial = 0; 
   var $k48_cadconvenio = 0; 
   var $k48_cadtipomod = 0; 
   var $k48_instit = 0; 
   var $k48_dataini_dia = null; 
   var $k48_dataini_mes = null; 
   var $k48_dataini_ano = null; 
   var $k48_dataini = null; 
   var $k48_datafim_dia = null; 
   var $k48_datafim_mes = null; 
   var $k48_datafim_ano = null; 
   var $k48_datafim = null; 
   var $k48_parcini = 0; 
   var $k48_parcfim = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k48_sequencial = int4 = Codigo do modelo padrão da instituição 
                 k48_cadconvenio = int4 = Convênio 
                 k48_cadtipomod = int4 = Codigo do tipo de modelo 
                 k48_instit = int4 = codigo da instituicao 
                 k48_dataini = date = Data inicial 
                 k48_datafim = date = Data final 
                 k48_parcini = float4 = Parcela Inicial 
                 k48_parcfim = float4 = Parcela Final 
                 ";
   //funcao construtor da classe 
   function cl_modcarnepadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("modcarnepadrao"); 
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
       $this->k48_sequencial = ($this->k48_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_sequencial"]:$this->k48_sequencial);
       $this->k48_cadconvenio = ($this->k48_cadconvenio == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_cadconvenio"]:$this->k48_cadconvenio);
       $this->k48_cadtipomod = ($this->k48_cadtipomod == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_cadtipomod"]:$this->k48_cadtipomod);
       $this->k48_instit = ($this->k48_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_instit"]:$this->k48_instit);
       if($this->k48_dataini == ""){
         $this->k48_dataini_dia = ($this->k48_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_dataini_dia"]:$this->k48_dataini_dia);
         $this->k48_dataini_mes = ($this->k48_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_dataini_mes"]:$this->k48_dataini_mes);
         $this->k48_dataini_ano = ($this->k48_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_dataini_ano"]:$this->k48_dataini_ano);
         if($this->k48_dataini_dia != ""){
            $this->k48_dataini = $this->k48_dataini_ano."-".$this->k48_dataini_mes."-".$this->k48_dataini_dia;
         }
       }
       if($this->k48_datafim == ""){
         $this->k48_datafim_dia = ($this->k48_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_datafim_dia"]:$this->k48_datafim_dia);
         $this->k48_datafim_mes = ($this->k48_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_datafim_mes"]:$this->k48_datafim_mes);
         $this->k48_datafim_ano = ($this->k48_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_datafim_ano"]:$this->k48_datafim_ano);
         if($this->k48_datafim_dia != ""){
            $this->k48_datafim = $this->k48_datafim_ano."-".$this->k48_datafim_mes."-".$this->k48_datafim_dia;
         }
       }
       $this->k48_parcini = ($this->k48_parcini == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_parcini"]:$this->k48_parcini);
       $this->k48_parcfim = ($this->k48_parcfim == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_parcfim"]:$this->k48_parcfim);       
     }else{
       $this->k48_sequencial = ($this->k48_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k48_sequencial"]:$this->k48_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k48_sequencial){ 
      $this->atualizacampos();
     if($this->k48_cadconvenio == null ){ 
       $this->erro_sql = " Campo Convênio nao Informado.";
       $this->erro_campo = "k48_cadconvenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k48_cadtipomod == null ){ 
       $this->erro_sql = " Campo Codigo do tipo de modelo nao Informado.";
       $this->erro_campo = "k48_cadtipomod";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k48_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "k48_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k48_dataini == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "k48_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k48_datafim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "k48_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k48_parcini == null ){ 
       $this->k48_parcini = "0";
     }
     if($this->k48_parcfim == null ){ 
       $this->k48_parcfim = "0";
     }     
     if($k48_sequencial == "" || $k48_sequencial == null ){
       $result = db_query("select nextval('modcarnepadrao_k48_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: modcarnepadrao_k48_sequencial_seq do campo: k48_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k48_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from modcarnepadrao_k48_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k48_sequencial)){
         $this->erro_sql = " Campo k48_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k48_sequencial = $k48_sequencial; 
       }
     }
     if(($this->k48_sequencial == null) || ($this->k48_sequencial == "") ){ 
       $this->erro_sql = " Campo k48_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into modcarnepadrao(
                                       k48_sequencial 
                                      ,k48_cadconvenio 
                                      ,k48_cadtipomod 
                                      ,k48_instit 
                                      ,k48_dataini 
                                      ,k48_datafim 
                                      ,k48_parcini 
                                      ,k48_parcfim 
                       )
                values (
                                $this->k48_sequencial 
                               ,$this->k48_cadconvenio 
                               ,$this->k48_cadtipomod 
                               ,$this->k48_instit 
                               ,".($this->k48_dataini == "null" || $this->k48_dataini == ""?"null":"'".$this->k48_dataini."'")." 
                               ,".($this->k48_datafim == "null" || $this->k48_datafim == ""?"null":"'".$this->k48_datafim."'")." 
                               ,$this->k48_parcini 
                               ,$this->k48_parcfim 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Modelo padrão  ($this->k48_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Modelo padrão  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Modelo padrão  ($this->k48_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k48_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k48_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8881,'$this->k48_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1516,8881,'','".AddSlashes(pg_result($resaco,0,'k48_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1516,12549,'','".AddSlashes(pg_result($resaco,0,'k48_cadconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1516,8884,'','".AddSlashes(pg_result($resaco,0,'k48_cadtipomod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1516,8882,'','".AddSlashes(pg_result($resaco,0,'k48_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1516,8885,'','".AddSlashes(pg_result($resaco,0,'k48_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1516,8886,'','".AddSlashes(pg_result($resaco,0,'k48_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k48_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update modcarnepadrao set ";
     $virgula = "";
     if(trim($this->k48_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k48_sequencial"])){ 
       $sql  .= $virgula." k48_sequencial = $this->k48_sequencial ";
       $virgula = ",";
       if(trim($this->k48_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo do modelo padrão da instituição nao Informado.";
         $this->erro_campo = "k48_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k48_cadconvenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k48_cadconvenio"])){ 
       $sql  .= $virgula." k48_cadconvenio = $this->k48_cadconvenio ";
       $virgula = ",";
       if(trim($this->k48_cadconvenio) == null ){ 
         $this->erro_sql = " Campo Convênio nao Informado.";
         $this->erro_campo = "k48_cadconvenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k48_cadtipomod)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k48_cadtipomod"])){ 
       $sql  .= $virgula." k48_cadtipomod = $this->k48_cadtipomod ";
       $virgula = ",";
       if(trim($this->k48_cadtipomod) == null ){ 
         $this->erro_sql = " Campo Codigo do tipo de modelo nao Informado.";
         $this->erro_campo = "k48_cadtipomod";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k48_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k48_instit"])){ 
       $sql  .= $virgula." k48_instit = $this->k48_instit ";
       $virgula = ",";
       if(trim($this->k48_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "k48_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k48_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k48_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k48_dataini_dia"] !="") ){ 
       $sql  .= $virgula." k48_dataini = '$this->k48_dataini' ";
       $virgula = ",";
       if(trim($this->k48_dataini) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "k48_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k48_dataini_dia"])){ 
         $sql  .= $virgula." k48_dataini = null ";
         $virgula = ",";
         if(trim($this->k48_dataini) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "k48_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k48_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k48_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k48_datafim_dia"] !="") ){ 
       $sql  .= $virgula." k48_datafim = '$this->k48_datafim' ";
       $virgula = ",";
       if(trim($this->k48_datafim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "k48_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k48_datafim_dia"])){ 
         $sql  .= $virgula." k48_datafim = null ";
         $virgula = ",";
         if(trim($this->k48_datafim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "k48_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k48_parcini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k48_parcini"])){ 
     if(trim($this->k48_parcini)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k48_parcini"])){ 
           $this->k48_parcini = "0" ; 
        } 
       $sql  .= $virgula." k48_parcini = $this->k48_parcini ";
       $virgula = ",";
     }
     if(trim($this->k48_parcfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k48_parcfim"])){ 
        if(trim($this->k48_parcfim)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k48_parcfim"])){ 
           $this->k48_parcfim = "0" ; 
        } 
       $sql  .= $virgula." k48_parcfim = $this->k48_parcfim ";
       $virgula = ",";
     }     
     $sql .= " where ";
     if($k48_sequencial!=null){
       $sql .= " k48_sequencial = $this->k48_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k48_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8881,'$this->k48_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k48_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1516,8881,'".AddSlashes(pg_result($resaco,$conresaco,'k48_sequencial'))."','$this->k48_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k48_cadconvenio"]))
           $resac = db_query("insert into db_acount values($acount,1516,12549,'".AddSlashes(pg_result($resaco,$conresaco,'k48_cadconvenio'))."','$this->k48_cadconvenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k48_cadtipomod"]))
           $resac = db_query("insert into db_acount values($acount,1516,8884,'".AddSlashes(pg_result($resaco,$conresaco,'k48_cadtipomod'))."','$this->k48_cadtipomod',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k48_instit"]))
           $resac = db_query("insert into db_acount values($acount,1516,8882,'".AddSlashes(pg_result($resaco,$conresaco,'k48_instit'))."','$this->k48_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k48_dataini"]))
           $resac = db_query("insert into db_acount values($acount,1516,8885,'".AddSlashes(pg_result($resaco,$conresaco,'k48_dataini'))."','$this->k48_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k48_datafim"]))
           $resac = db_query("insert into db_acount values($acount,1516,8886,'".AddSlashes(pg_result($resaco,$conresaco,'k48_datafim'))."','$this->k48_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k48_parcini"]) || $this->k48_parcini != "")
           $resac = db_query("insert into db_acount values($acount,1516,1009250,'".AddSlashes(pg_result($resaco,$conresaco,'k48_parcini'))."','$this->k48_parcini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k48_parcfim"]) || $this->k48_parcfim != "")
           $resac = db_query("insert into db_acount values($acount,1516,1009251,'".AddSlashes(pg_result($resaco,$conresaco,'k48_parcfim'))."','$this->k48_parcfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");       
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Modelo padrão  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k48_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Modelo padrão  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k48_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k48_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8881,'$k48_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1516,8881,'','".AddSlashes(pg_result($resaco,$iresaco,'k48_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1516,12549,'','".AddSlashes(pg_result($resaco,$iresaco,'k48_cadconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1516,8884,'','".AddSlashes(pg_result($resaco,$iresaco,'k48_cadtipomod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1516,8882,'','".AddSlashes(pg_result($resaco,$iresaco,'k48_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1516,8885,'','".AddSlashes(pg_result($resaco,$iresaco,'k48_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1516,8886,'','".AddSlashes(pg_result($resaco,$iresaco,'k48_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1516,1009250,'','".AddSlashes(pg_result($resaco,$iresaco,'k48_parcini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1516,1009251,'','".AddSlashes(pg_result($resaco,$iresaco,'k48_parcfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from modcarnepadrao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k48_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k48_sequencial = $k48_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Modelo padrão  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k48_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Modelo padrão  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k48_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:modcarnepadrao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadrao 																											";
     $sql .= "      inner join db_config       			 on db_config.codigo            			     = modcarnepadrao.k48_instit		";
     $sql .= "      inner join cadtipomod       	 	 on cadtipomod.k46_sequencial       		  	 = modcarnepadrao.k48_cadtipomod	";
     $sql .= "      inner join cadconvenio     			 on cadconvenio.ar11_sequencial    				 = modcarnepadrao.k48_cadconvenio	";
     $sql .= "      inner join cgm             			 on cgm.z01_numcgm                 				 = db_config.numcgm					";
     $sql .= "      inner join cadtipoconvenio 			 on cadtipoconvenio.ar12_sequencial 			 = cadconvenio.ar11_cadtipoconvenio ";
	 $sql .= "      left  join modcarnepadraotipo	     on modcarnepadraotipo.k49_modcarnepadrao 	     = modcarnepadrao.k48_sequencial 	";
	 $sql .= "      left  join modcarneexcessao	 	     on modcarneexcessao.k36_modcarnepadrao		     = modcarnepadrao.k48_sequencial 	";     
     $sql .= "      left  join modcarnepadraolayouttxt   on modcarnepadraolayouttxt.m02_modcarnepadrao   = modcarnepadrao.k48_sequencial 	";
     $sql .= "      left  join modcarnepadraocadmodcarne on modcarnepadraocadmodcarne.m01_modcarnepadrao = modcarnepadrao.k48_sequencial 	";     
     $sql2 = "";
     if($dbwhere==""){
       if($k48_sequencial!=null ){
         $sql2 .= " where modcarnepadrao.k48_sequencial = $k48_sequencial "; 
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
   function sql_query_file ( $k48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($k48_sequencial!=null ){
         $sql2 .= " where modcarnepadrao.k48_sequencial = $k48_sequencial "; 
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

   function sql_query_func ( $k48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadrao 																									   				 ";
     $sql .= "      inner join db_config    			 on db_config.codigo 							 = modcarnepadrao.k48_instit	    		 ";
     $sql .= "      inner join cgm  					 on cgm.z01_numcgm 								 = db_config.numcgm				    		 ";
     $sql .= "      inner join cadtipomod   			 on cadtipomod.k46_sequencial  	    			 = modcarnepadrao.k48_cadtipomod    		 ";
     $sql .= "      left  join cadconvenio  			 on cadconvenio.ar11_sequencial 				 = modcarnepadrao.k48_cadconvenio   		 ";
     $sql .= "      left  join cadtipoconvenio 			 on cadtipoconvenio.ar12_sequencial 			 = cadconvenio.ar11_cadtipoconvenio 		 ";
     $sql .= "      left  join modcarnepadraolayouttxt   on modcarnepadraolayouttxt.m02_modcarnepadrao   = modcarnepadrao.k48_sequencial 			 ";
     $sql .= "      left  join db_layouttxt			     on db_layouttxt.db50_codigo				     = modcarnepadraolayouttxt.m02_db_layouttxt  ";
     $sql .= "      left  join modcarnepadraocadmodcarne on modcarnepadraocadmodcarne.m01_modcarnepadrao = modcarnepadrao.k48_sequencial 			 ";     
     $sql .= "      left  join cadmodcarne 				 on cadmodcarne.k47_sequencial					 = modcarnepadraocadmodcarne.m01_cadmodcarne ";     
	 $sql .= "      left  join modcarnepadraotipo	     on modcarnepadraotipo.k49_modcarnepadrao 	     = modcarnepadrao.k48_sequencial 			 ";
	 $sql .= "      left  join modcarneexcessao	 	     on modcarneexcessao.k36_modcarnepadrao		     = modcarnepadrao.k48_sequencial 			 ";     
     $sql2 = "";
     
     if($dbwhere==""){
       if($k48_sequencial!=null ){
         $sql2 .= " where modcarnepadrao.k48_sequencial = $k48_sequencial "; 
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