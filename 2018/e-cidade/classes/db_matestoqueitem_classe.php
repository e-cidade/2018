<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueitem
class cl_matestoqueitem { 
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
   var $m71_codlanc = 0; 
   var $m71_codmatestoque = 0; 
   var $m71_data_dia = null; 
   var $m71_data_mes = null; 
   var $m71_data_ano = null; 
   var $m71_data = null; 
   var $m71_quant = 0; 
   var $m71_valor = 0; 
   var $m71_quantatend = 0; 
   var $m71_servico = 'false'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m71_codlanc = int8 = Código sequencial do lançamento 
                 m71_codmatestoque = int8 = Codigo sequencial do registro 
                 m71_data = date = Data da entrada 
                 m71_quant = float8 = Quantidade de entrada 
                 m71_valor = float8 = Valor do item 
                 m71_quantatend = float8 = Quant. Atendida 
                 m71_servico = bool = Serviço 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueitem"); 
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
       $this->m71_codlanc = ($this->m71_codlanc == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_codlanc"]:$this->m71_codlanc);
       $this->m71_codmatestoque = ($this->m71_codmatestoque == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_codmatestoque"]:$this->m71_codmatestoque);
       if($this->m71_data == ""){
         $this->m71_data_dia = ($this->m71_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_data_dia"]:$this->m71_data_dia);
         $this->m71_data_mes = ($this->m71_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_data_mes"]:$this->m71_data_mes);
         $this->m71_data_ano = ($this->m71_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_data_ano"]:$this->m71_data_ano);
         if($this->m71_data_dia != ""){
            $this->m71_data = $this->m71_data_ano."-".$this->m71_data_mes."-".$this->m71_data_dia;
         }
       }
       $this->m71_quant = ($this->m71_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_quant"]:$this->m71_quant);
       $this->m71_valor = ($this->m71_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_valor"]:$this->m71_valor);
       $this->m71_quantatend = ($this->m71_quantatend == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_quantatend"]:$this->m71_quantatend);
       $this->m71_servico = ($this->m71_servico == "f"?@$GLOBALS["HTTP_POST_VARS"]["m71_servico"]:$this->m71_servico);
     }else{
       $this->m71_codlanc = ($this->m71_codlanc == ""?@$GLOBALS["HTTP_POST_VARS"]["m71_codlanc"]:$this->m71_codlanc);
     }
   }
   // funcao para inclusao
   function incluir ($m71_codlanc){ 
      $this->atualizacampos();
     if($this->m71_codmatestoque == null ){ 
       $this->erro_sql = " Campo Codigo sequencial do registro nao Informado.";
       $this->erro_campo = "m71_codmatestoque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m71_data == null ){ 
       $this->erro_sql = " Campo Data da entrada nao Informado.";
       $this->erro_campo = "m71_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m71_quant == null ){ 
       $this->erro_sql = " Campo Quantidade de entrada nao Informado.";
       $this->erro_campo = "m71_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m71_valor == null ){ 
       $this->erro_sql = " Campo Valor do item nao Informado.";
       $this->erro_campo = "m71_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m71_quantatend == null ){ 
       $this->erro_sql = " Campo Quant. Atendida nao Informado.";
       $this->erro_campo = "m71_quantatend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m71_servico == null ){ 
       $this->m71_servico = "false";
     }
     if($m71_codlanc == "" || $m71_codlanc == null ){
       $result = db_query("select nextval('matestoqueitem_m71_codlanc_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueitem_m71_codlanc_seq do campo: m71_codlanc"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m71_codlanc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoqueitem_m71_codlanc_seq");
       if(($result != false) && (pg_result($result,0,0) < $m71_codlanc)){
         $this->erro_sql = " Campo m71_codlanc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m71_codlanc = $m71_codlanc; 
       }
     }
     if(($this->m71_codlanc == null) || ($this->m71_codlanc == "") ){ 
       $this->erro_sql = " Campo m71_codlanc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitem(
                                       m71_codlanc 
                                      ,m71_codmatestoque 
                                      ,m71_data 
                                      ,m71_quant 
                                      ,m71_valor 
                                      ,m71_quantatend 
                                      ,m71_servico 
                       )
                values (
                                $this->m71_codlanc 
                               ,$this->m71_codmatestoque 
                               ,".($this->m71_data == "null" || $this->m71_data == ""?"null":"'".$this->m71_data."'")." 
                               ,$this->m71_quant 
                               ,$this->m71_valor 
                               ,$this->m71_quantatend 
                               ,'$this->m71_servico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens do estoque ($this->m71_codlanc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens do estoque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens do estoque ($this->m71_codlanc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m71_codlanc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m71_codlanc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6280,'$this->m71_codlanc','I')");
       $resac = db_query("insert into db_acount values($acount,1020,6280,'','".AddSlashes(pg_result($resaco,0,'m71_codlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1020,6274,'','".AddSlashes(pg_result($resaco,0,'m71_codmatestoque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1020,6275,'','".AddSlashes(pg_result($resaco,0,'m71_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1020,6276,'','".AddSlashes(pg_result($resaco,0,'m71_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1020,6277,'','".AddSlashes(pg_result($resaco,0,'m71_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1020,6887,'','".AddSlashes(pg_result($resaco,0,'m71_quantatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1020,17956,'','".AddSlashes(pg_result($resaco,0,'m71_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m71_codlanc=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueitem set ";
     $virgula = "";
     if(trim($this->m71_codlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m71_codlanc"])){ 
       $sql  .= $virgula." m71_codlanc = $this->m71_codlanc ";
       $virgula = ",";
       if(trim($this->m71_codlanc) == null ){ 
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m71_codlanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m71_codmatestoque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m71_codmatestoque"])){ 
       $sql  .= $virgula." m71_codmatestoque = $this->m71_codmatestoque ";
       $virgula = ",";
       if(trim($this->m71_codmatestoque) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial do registro nao Informado.";
         $this->erro_campo = "m71_codmatestoque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m71_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m71_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m71_data_dia"] !="") ){ 
       $sql  .= $virgula." m71_data = '$this->m71_data' ";
       $virgula = ",";
       if(trim($this->m71_data) == null ){ 
         $this->erro_sql = " Campo Data da entrada nao Informado.";
         $this->erro_campo = "m71_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m71_data_dia"])){ 
         $sql  .= $virgula." m71_data = null ";
         $virgula = ",";
         if(trim($this->m71_data) == null ){ 
           $this->erro_sql = " Campo Data da entrada nao Informado.";
           $this->erro_campo = "m71_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m71_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m71_quant"])){ 
       $sql  .= $virgula." m71_quant = $this->m71_quant ";
       $virgula = ",";
       if(trim($this->m71_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade de entrada nao Informado.";
         $this->erro_campo = "m71_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m71_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m71_valor"])){ 
       $sql  .= $virgula." m71_valor = $this->m71_valor ";
       $virgula = ",";
       if(trim($this->m71_valor) == null ){ 
         $this->erro_sql = " Campo Valor do item nao Informado.";
         $this->erro_campo = "m71_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m71_quantatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m71_quantatend"])){ 
       $sql  .= $virgula." m71_quantatend = $this->m71_quantatend ";
       $virgula = ",";
       if(trim($this->m71_quantatend) == null ){ 
         $this->erro_sql = " Campo Quant. Atendida nao Informado.";
         $this->erro_campo = "m71_quantatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m71_servico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m71_servico"])){ 
       $sql  .= $virgula." m71_servico = '$this->m71_servico' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m71_codlanc!=null){
       $sql .= " m71_codlanc = $this->m71_codlanc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m71_codlanc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6280,'$this->m71_codlanc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m71_codlanc"]) || $this->m71_codlanc != "")
           $resac = db_query("insert into db_acount values($acount,1020,6280,'".AddSlashes(pg_result($resaco,$conresaco,'m71_codlanc'))."','$this->m71_codlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m71_codmatestoque"]) || $this->m71_codmatestoque != "")
           $resac = db_query("insert into db_acount values($acount,1020,6274,'".AddSlashes(pg_result($resaco,$conresaco,'m71_codmatestoque'))."','$this->m71_codmatestoque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m71_data"]) || $this->m71_data != "")
           $resac = db_query("insert into db_acount values($acount,1020,6275,'".AddSlashes(pg_result($resaco,$conresaco,'m71_data'))."','$this->m71_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m71_quant"]) || $this->m71_quant != "")
           $resac = db_query("insert into db_acount values($acount,1020,6276,'".AddSlashes(pg_result($resaco,$conresaco,'m71_quant'))."','$this->m71_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m71_valor"]) || $this->m71_valor != "")
           $resac = db_query("insert into db_acount values($acount,1020,6277,'".AddSlashes(pg_result($resaco,$conresaco,'m71_valor'))."','$this->m71_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m71_quantatend"]) || $this->m71_quantatend != "")
           $resac = db_query("insert into db_acount values($acount,1020,6887,'".AddSlashes(pg_result($resaco,$conresaco,'m71_quantatend'))."','$this->m71_quantatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m71_servico"]) || $this->m71_servico != "")
           $resac = db_query("insert into db_acount values($acount,1020,17956,'".AddSlashes(pg_result($resaco,$conresaco,'m71_servico'))."','$this->m71_servico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens do estoque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m71_codlanc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens do estoque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m71_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m71_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m71_codlanc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m71_codlanc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6280,'$m71_codlanc','E')");
         $resac = db_query("insert into db_acount values($acount,1020,6280,'','".AddSlashes(pg_result($resaco,$iresaco,'m71_codlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1020,6274,'','".AddSlashes(pg_result($resaco,$iresaco,'m71_codmatestoque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1020,6275,'','".AddSlashes(pg_result($resaco,$iresaco,'m71_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1020,6276,'','".AddSlashes(pg_result($resaco,$iresaco,'m71_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1020,6277,'','".AddSlashes(pg_result($resaco,$iresaco,'m71_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1020,6887,'','".AddSlashes(pg_result($resaco,$iresaco,'m71_quantatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1020,17956,'','".AddSlashes(pg_result($resaco,$iresaco,'m71_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m71_codlanc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m71_codlanc = $m71_codlanc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens do estoque nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m71_codlanc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens do estoque nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m71_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m71_codlanc;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m71_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitem ";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
     $sql2 = "";
     if($dbwhere==""){
       if($m71_codlanc!=null ){
         $sql2 .= " where matestoqueitem.m71_codlanc = $m71_codlanc "; 
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
   function sql_query_file ( $m71_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m71_codlanc!=null ){
         $sql2 .= " where matestoqueitem.m71_codlanc = $m71_codlanc "; 
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
   function sql_query_unid ( $m71_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitem ";
     $sql .= "      inner join matestoqueitemunid on matestoqueitemunid.m75_codmatestoqueitem = matestoqueitem.m71_codlanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($m71_codlanc!=null ){
         $sql2 .= " where matestoqueitem.m71_codlanc = $m71_codlanc "; 
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
   function sql_query_lote ( $m71_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitem ";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
     $sql .= "      left join matestoqueitemlote  on  m71_codlanc = m77_matestoqueitem";
     $sql .= "      left join matestoqueitemfabric  on  m71_codlanc = m78_matestoqueitem";
     $sql .= "      left join matfabricante  on  m78_matfabricante = m76_sequencial";
     $sql .= "      left join cgm  on  m76_numcgm = z01_numcgm";
//     $sql .= "      left  join matestoqueitemlanc on matestoqueitemlanc.m95_codlanc = matestoqueitem.m71_codlanc ";
     $sql2 = " where ";
     $and  = "";
     if($dbwhere==""){
       if($m71_codlanc!=null ){
         $sql2 .= " matestoqueitem.m71_codlanc = $m71_codlanc ";
	 $and = " and ";
       } 
     }else if($dbwhere != ""){
       $sql2 .= " $dbwhere";
       $and = " and ";
     }
  //   $sql2 .= $and . " matestoqueitemlanc.m95_codlanc is null ";
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