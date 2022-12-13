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
//CLASSE DA ENTIDADE orcparamrelopcre
class cl_orcparamrelopcre { 
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
   var $o98_sequencial = 0; 
   var $o98_orcparamseq = 0; 
   var $o98_orcparamrel = 0; 
   var $o98_anousu = 0; 
   var $o98_instit = 0; 
   var $o98_identificacao = null; 
   var $o98_credor = null; 
   var $o98_periodo = null; 
   var $o98_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o98_sequencial = int4 = Código Sequencial 
                 o98_orcparamseq = int4 = Código do Parametro 
                 o98_orcparamrel = int4 = Código do Relátorio 
                 o98_anousu = int4 = Ano 
                 o98_instit = int4 = Instituição 
                 o98_identificacao = varchar(75) = Ident. da Op. de Crédito 
                 o98_credor = varchar(75) = Credor 
                 o98_periodo = char(2) = Período 
                 o98_valor = float4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_orcparamrelopcre() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamrelopcre"); 
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
       $this->o98_sequencial = ($this->o98_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_sequencial"]:$this->o98_sequencial);
       $this->o98_orcparamseq = ($this->o98_orcparamseq == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_orcparamseq"]:$this->o98_orcparamseq);
       $this->o98_orcparamrel = ($this->o98_orcparamrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_orcparamrel"]:$this->o98_orcparamrel);
       $this->o98_anousu = ($this->o98_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_anousu"]:$this->o98_anousu);
       $this->o98_instit = ($this->o98_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_instit"]:$this->o98_instit);
       $this->o98_identificacao = ($this->o98_identificacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_identificacao"]:$this->o98_identificacao);
       $this->o98_credor = ($this->o98_credor == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_credor"]:$this->o98_credor);
       $this->o98_periodo = ($this->o98_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_periodo"]:$this->o98_periodo);
       $this->o98_valor = ($this->o98_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_valor"]:$this->o98_valor);
     }else{
       $this->o98_sequencial = ($this->o98_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o98_sequencial"]:$this->o98_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o98_sequencial){ 
      $this->atualizacampos();
     if($this->o98_orcparamseq == null ){ 
       $this->erro_sql = " Campo Código do Parametro nao Informado.";
       $this->erro_campo = "o98_orcparamseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o98_orcparamrel == null ){ 
       $this->erro_sql = " Campo Código do Relátorio nao Informado.";
       $this->erro_campo = "o98_orcparamrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o98_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "o98_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o98_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "o98_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o98_identificacao == null ){ 
       $this->erro_sql = " Campo Ident. da Op. de Crédito nao Informado.";
       $this->erro_campo = "o98_identificacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o98_credor == null ){ 
       $this->erro_sql = " Campo Credor nao Informado.";
       $this->erro_campo = "o98_credor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o98_periodo == null ){ 
       $this->erro_sql = " Campo Período nao Informado.";
       $this->erro_campo = "o98_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o98_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o98_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o98_sequencial == "" || $o98_sequencial == null ){
       $result = db_query("select nextval('orcparamrelopcre_o98_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamrelopcre_o98_sequencial_seq do campo: o98_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o98_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamrelopcre_o98_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o98_sequencial)){
         $this->erro_sql = " Campo o98_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o98_sequencial = $o98_sequencial; 
       }
     }
     if(($this->o98_sequencial == null) || ($this->o98_sequencial == "") ){ 
       $this->erro_sql = " Campo o98_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamrelopcre(
                                       o98_sequencial 
                                      ,o98_orcparamseq 
                                      ,o98_orcparamrel 
                                      ,o98_anousu 
                                      ,o98_instit 
                                      ,o98_identificacao 
                                      ,o98_credor 
                                      ,o98_periodo 
                                      ,o98_valor 
                       )
                values (
                                $this->o98_sequencial 
                               ,$this->o98_orcparamseq 
                               ,$this->o98_orcparamrel 
                               ,$this->o98_anousu 
                               ,$this->o98_instit 
                               ,'$this->o98_identificacao' 
                               ,'$this->o98_credor' 
                               ,'$this->o98_periodo' 
                               ,$this->o98_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros Op. crédito ($this->o98_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros Op. crédito já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros Op. crédito ($this->o98_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o98_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o98_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11193,'$this->o98_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1934,11193,'','".AddSlashes(pg_result($resaco,0,'o98_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1934,11194,'','".AddSlashes(pg_result($resaco,0,'o98_orcparamseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1934,11206,'','".AddSlashes(pg_result($resaco,0,'o98_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1934,11195,'','".AddSlashes(pg_result($resaco,0,'o98_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1934,11196,'','".AddSlashes(pg_result($resaco,0,'o98_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1934,11198,'','".AddSlashes(pg_result($resaco,0,'o98_identificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1934,11199,'','".AddSlashes(pg_result($resaco,0,'o98_credor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1934,11202,'','".AddSlashes(pg_result($resaco,0,'o98_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1934,11203,'','".AddSlashes(pg_result($resaco,0,'o98_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o98_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcparamrelopcre set ";
     $virgula = "";
     if(trim($this->o98_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_sequencial"])){ 
       $sql  .= $virgula." o98_sequencial = $this->o98_sequencial ";
       $virgula = ",";
       if(trim($this->o98_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o98_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o98_orcparamseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_orcparamseq"])){ 
       $sql  .= $virgula." o98_orcparamseq = $this->o98_orcparamseq ";
       $virgula = ",";
       if(trim($this->o98_orcparamseq) == null ){ 
         $this->erro_sql = " Campo Código do Parametro nao Informado.";
         $this->erro_campo = "o98_orcparamseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o98_orcparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_orcparamrel"])){ 
       $sql  .= $virgula." o98_orcparamrel = $this->o98_orcparamrel ";
       $virgula = ",";
       if(trim($this->o98_orcparamrel) == null ){ 
         $this->erro_sql = " Campo Código do Relátorio nao Informado.";
         $this->erro_campo = "o98_orcparamrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o98_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_anousu"])){ 
       $sql  .= $virgula." o98_anousu = $this->o98_anousu ";
       $virgula = ",";
       if(trim($this->o98_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "o98_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o98_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_instit"])){ 
       $sql  .= $virgula." o98_instit = $this->o98_instit ";
       $virgula = ",";
       if(trim($this->o98_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "o98_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o98_identificacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_identificacao"])){ 
       $sql  .= $virgula." o98_identificacao = '$this->o98_identificacao' ";
       $virgula = ",";
       if(trim($this->o98_identificacao) == null ){ 
         $this->erro_sql = " Campo Ident. da Op. de Crédito nao Informado.";
         $this->erro_campo = "o98_identificacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o98_credor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_credor"])){ 
       $sql  .= $virgula." o98_credor = '$this->o98_credor' ";
       $virgula = ",";
       if(trim($this->o98_credor) == null ){ 
         $this->erro_sql = " Campo Credor nao Informado.";
         $this->erro_campo = "o98_credor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o98_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_periodo"])){ 
       $sql  .= $virgula." o98_periodo = '$this->o98_periodo' ";
       $virgula = ",";
       if(trim($this->o98_periodo) == null ){ 
         $this->erro_sql = " Campo Período nao Informado.";
         $this->erro_campo = "o98_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o98_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o98_valor"])){ 
       $sql  .= $virgula." o98_valor = $this->o98_valor ";
       $virgula = ",";
       if(trim($this->o98_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o98_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o98_sequencial!=null){
       $sql .= " o98_sequencial = $this->o98_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o98_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11193,'$this->o98_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1934,11193,'".AddSlashes(pg_result($resaco,$conresaco,'o98_sequencial'))."','$this->o98_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_orcparamseq"]))
           $resac = db_query("insert into db_acount values($acount,1934,11194,'".AddSlashes(pg_result($resaco,$conresaco,'o98_orcparamseq'))."','$this->o98_orcparamseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_orcparamrel"]))
           $resac = db_query("insert into db_acount values($acount,1934,11206,'".AddSlashes(pg_result($resaco,$conresaco,'o98_orcparamrel'))."','$this->o98_orcparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1934,11195,'".AddSlashes(pg_result($resaco,$conresaco,'o98_anousu'))."','$this->o98_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_instit"]))
           $resac = db_query("insert into db_acount values($acount,1934,11196,'".AddSlashes(pg_result($resaco,$conresaco,'o98_instit'))."','$this->o98_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_identificacao"]))
           $resac = db_query("insert into db_acount values($acount,1934,11198,'".AddSlashes(pg_result($resaco,$conresaco,'o98_identificacao'))."','$this->o98_identificacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_credor"]))
           $resac = db_query("insert into db_acount values($acount,1934,11199,'".AddSlashes(pg_result($resaco,$conresaco,'o98_credor'))."','$this->o98_credor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_periodo"]))
           $resac = db_query("insert into db_acount values($acount,1934,11202,'".AddSlashes(pg_result($resaco,$conresaco,'o98_periodo'))."','$this->o98_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o98_valor"]))
           $resac = db_query("insert into db_acount values($acount,1934,11203,'".AddSlashes(pg_result($resaco,$conresaco,'o98_valor'))."','$this->o98_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros Op. crédito nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o98_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros Op. crédito nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o98_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o98_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11193,'$o98_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1934,11193,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1934,11194,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_orcparamseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1934,11206,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1934,11195,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1934,11196,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1934,11198,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_identificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1934,11199,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_credor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1934,11202,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1934,11203,'','".AddSlashes(pg_result($resaco,$iresaco,'o98_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamrelopcre
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o98_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o98_sequencial = $o98_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros Op. crédito nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o98_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros Op. crédito nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o98_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamrelopcre";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrelopcre ";
     $sql .= "      inner join orcparamseq  on  orcparamseq.o69_codparamrel = orcparamrelopcre.o98_orcparamrel
                                           and  orcparamseq.o69_codseq = orcparamrelopcre.o98_orcparamseq";
     $sql2 = "";
     if($dbwhere==""){
       if($o98_sequencial!=null ){
         $sql2 .= " where orcparamrelopcre.o98_sequencial = $o98_sequencial "; 
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
   function sql_query_file ( $o98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrelopcre ";
     $sql2 = "";
     if($dbwhere==""){
       if($o98_sequencial!=null ){
         $sql2 .= " where orcparamrelopcre.o98_sequencial = $o98_sequencial "; 
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