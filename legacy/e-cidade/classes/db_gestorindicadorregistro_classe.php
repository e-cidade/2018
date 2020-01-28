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

//MODULO: Gestor BI
//CLASSE DA ENTIDADE gestorindicadorregistro
class cl_gestorindicadorregistro { 
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
   var $g05_sequencial = 0; 
   var $g05_gestorgrupoindicador = 0; 
   var $g05_gestorindicador = 0; 
   var $g05_instit = 0; 
   var $g05_mes = 0; 
   var $g05_ano = 0; 
   var $g05_valor = 0; 
   var $g05_meta = 0; 
   var $g05_processado = 'f'; 
   var $g05_datalimite_dia = null; 
   var $g05_datalimite_mes = null; 
   var $g05_datalimite_ano = null; 
   var $g05_datalimite = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 g05_sequencial = int4 = Código Sequencial 
                 g05_gestorgrupoindicador = int4 = Grupo 
                 g05_gestorindicador = int4 = Indicador 
                 g05_instit = int4 = Instituição 
                 g05_mes = int4 = Mês 
                 g05_ano = int4 = Ano 
                 g05_valor = float8 = Valor 
                 g05_meta = float8 = Meta 
                 g05_processado = bool = Processado 
                 g05_datalimite = date = Data Limite 
                 ";
   //funcao construtor da classe 
   function cl_gestorindicadorregistro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gestorindicadorregistro"); 
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
       $this->g05_sequencial = ($this->g05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_sequencial"]:$this->g05_sequencial);
       $this->g05_gestorgrupoindicador = ($this->g05_gestorgrupoindicador == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_gestorgrupoindicador"]:$this->g05_gestorgrupoindicador);
       $this->g05_gestorindicador = ($this->g05_gestorindicador == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_gestorindicador"]:$this->g05_gestorindicador);
       $this->g05_instit = ($this->g05_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_instit"]:$this->g05_instit);
       $this->g05_mes = ($this->g05_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_mes"]:$this->g05_mes);
       $this->g05_ano = ($this->g05_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_ano"]:$this->g05_ano);
       $this->g05_valor = ($this->g05_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_valor"]:$this->g05_valor);
       $this->g05_meta = ($this->g05_meta == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_meta"]:$this->g05_meta);
       $this->g05_processado = ($this->g05_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["g05_processado"]:$this->g05_processado);
       if($this->g05_datalimite == ""){
         $this->g05_datalimite_dia = ($this->g05_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_datalimite_dia"]:$this->g05_datalimite_dia);
         $this->g05_datalimite_mes = ($this->g05_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_datalimite_mes"]:$this->g05_datalimite_mes);
         $this->g05_datalimite_ano = ($this->g05_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_datalimite_ano"]:$this->g05_datalimite_ano);
         if($this->g05_datalimite_dia != ""){
            $this->g05_datalimite = $this->g05_datalimite_ano."-".$this->g05_datalimite_mes."-".$this->g05_datalimite_dia;
         }
       }
     }else{
       $this->g05_sequencial = ($this->g05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["g05_sequencial"]:$this->g05_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($g05_sequencial){ 
      $this->atualizacampos();
     if($this->g05_gestorgrupoindicador == null ){ 
       $this->erro_sql = " Campo Grupo nao Informado.";
       $this->erro_campo = "g05_gestorgrupoindicador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g05_gestorindicador == null ){ 
       $this->erro_sql = " Campo Indicador nao Informado.";
       $this->erro_campo = "g05_gestorindicador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g05_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "g05_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g05_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "g05_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g05_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "g05_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g05_valor == null ){ 
       $this->g05_valor = "0";
     }
     if($this->g05_meta == null ){ 
       $this->g05_meta = "0";
     }
     if($this->g05_processado == null ){ 
       $this->g05_processado = "False";
     }
     if($this->g05_datalimite == null ){ 
       $this->g05_datalimite = "null";
     }
     if($g05_sequencial == "" || $g05_sequencial == null ){
       $result = db_query("select nextval('gestorindicadorregistro_g05_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: gestorindicadorregistro_g05_sequencial_seq do campo: g05_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->g05_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from gestorindicadorregistro_g05_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $g05_sequencial)){
         $this->erro_sql = " Campo g05_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->g05_sequencial = $g05_sequencial; 
       }
     }
     if(($this->g05_sequencial == null) || ($this->g05_sequencial == "") ){ 
       $this->erro_sql = " Campo g05_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gestorindicadorregistro(
                                       g05_sequencial 
                                      ,g05_gestorgrupoindicador 
                                      ,g05_gestorindicador 
                                      ,g05_instit 
                                      ,g05_mes 
                                      ,g05_ano 
                                      ,g05_valor 
                                      ,g05_meta 
                                      ,g05_processado 
                                      ,g05_datalimite 
                       )
                values (
                                $this->g05_sequencial 
                               ,$this->g05_gestorgrupoindicador 
                               ,$this->g05_gestorindicador 
                               ,$this->g05_instit 
                               ,$this->g05_mes 
                               ,$this->g05_ano 
                               ,$this->g05_valor 
                               ,$this->g05_meta 
                               ,'$this->g05_processado' 
                               ,".($this->g05_datalimite == "null" || $this->g05_datalimite == ""?"null":"'".$this->g05_datalimite."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "gestorindicadorregistro ($this->g05_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "gestorindicadorregistro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "gestorindicadorregistro ($this->g05_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->g05_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->g05_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16069,'$this->g05_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2818,16069,'','".AddSlashes(pg_result($resaco,0,'g05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16070,'','".AddSlashes(pg_result($resaco,0,'g05_gestorgrupoindicador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16071,'','".AddSlashes(pg_result($resaco,0,'g05_gestorindicador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16072,'','".AddSlashes(pg_result($resaco,0,'g05_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16073,'','".AddSlashes(pg_result($resaco,0,'g05_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16074,'','".AddSlashes(pg_result($resaco,0,'g05_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16075,'','".AddSlashes(pg_result($resaco,0,'g05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16076,'','".AddSlashes(pg_result($resaco,0,'g05_meta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16077,'','".AddSlashes(pg_result($resaco,0,'g05_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2818,16078,'','".AddSlashes(pg_result($resaco,0,'g05_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($g05_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update gestorindicadorregistro set ";
     $virgula = "";
     if(trim($this->g05_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_sequencial"])){ 
       $sql  .= $virgula." g05_sequencial = $this->g05_sequencial ";
       $virgula = ",";
       if(trim($this->g05_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "g05_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g05_gestorgrupoindicador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_gestorgrupoindicador"])){ 
       $sql  .= $virgula." g05_gestorgrupoindicador = $this->g05_gestorgrupoindicador ";
       $virgula = ",";
       if(trim($this->g05_gestorgrupoindicador) == null ){ 
         $this->erro_sql = " Campo Grupo nao Informado.";
         $this->erro_campo = "g05_gestorgrupoindicador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g05_gestorindicador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_gestorindicador"])){ 
       $sql  .= $virgula." g05_gestorindicador = $this->g05_gestorindicador ";
       $virgula = ",";
       if(trim($this->g05_gestorindicador) == null ){ 
         $this->erro_sql = " Campo Indicador nao Informado.";
         $this->erro_campo = "g05_gestorindicador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g05_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_instit"])){ 
       $sql  .= $virgula." g05_instit = $this->g05_instit ";
       $virgula = ",";
       if(trim($this->g05_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "g05_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g05_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_mes"])){ 
       $sql  .= $virgula." g05_mes = $this->g05_mes ";
       $virgula = ",";
       if(trim($this->g05_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "g05_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g05_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_ano"])){ 
       $sql  .= $virgula." g05_ano = $this->g05_ano ";
       $virgula = ",";
       if(trim($this->g05_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "g05_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g05_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_valor"])){ 
        if(trim($this->g05_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["g05_valor"])){ 
           $this->g05_valor = "0" ; 
        } 
       $sql  .= $virgula." g05_valor = $this->g05_valor ";
       $virgula = ",";
     }
     if(trim($this->g05_meta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_meta"])){ 
        if(trim($this->g05_meta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["g05_meta"])){ 
           $this->g05_meta = "0" ; 
        } 
       $sql  .= $virgula." g05_meta = $this->g05_meta ";
       $virgula = ",";
     }
     if(trim($this->g05_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_processado"])){ 
       $sql  .= $virgula." g05_processado = '$this->g05_processado' ";
       $virgula = ",";
     }
     if(trim($this->g05_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g05_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["g05_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." g05_datalimite = '$this->g05_datalimite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["g05_datalimite_dia"])){ 
         $sql  .= $virgula." g05_datalimite = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($g05_sequencial!=null){
       $sql .= " g05_sequencial = $this->g05_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->g05_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16069,'$this->g05_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_sequencial"]) || $this->g05_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2818,16069,'".AddSlashes(pg_result($resaco,$conresaco,'g05_sequencial'))."','$this->g05_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_gestorgrupoindicador"]) || $this->g05_gestorgrupoindicador != "")
           $resac = db_query("insert into db_acount values($acount,2818,16070,'".AddSlashes(pg_result($resaco,$conresaco,'g05_gestorgrupoindicador'))."','$this->g05_gestorgrupoindicador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_gestorindicador"]) || $this->g05_gestorindicador != "")
           $resac = db_query("insert into db_acount values($acount,2818,16071,'".AddSlashes(pg_result($resaco,$conresaco,'g05_gestorindicador'))."','$this->g05_gestorindicador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_instit"]) || $this->g05_instit != "")
           $resac = db_query("insert into db_acount values($acount,2818,16072,'".AddSlashes(pg_result($resaco,$conresaco,'g05_instit'))."','$this->g05_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_mes"]) || $this->g05_mes != "")
           $resac = db_query("insert into db_acount values($acount,2818,16073,'".AddSlashes(pg_result($resaco,$conresaco,'g05_mes'))."','$this->g05_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_ano"]) || $this->g05_ano != "")
           $resac = db_query("insert into db_acount values($acount,2818,16074,'".AddSlashes(pg_result($resaco,$conresaco,'g05_ano'))."','$this->g05_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_valor"]) || $this->g05_valor != "")
           $resac = db_query("insert into db_acount values($acount,2818,16075,'".AddSlashes(pg_result($resaco,$conresaco,'g05_valor'))."','$this->g05_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_meta"]) || $this->g05_meta != "")
           $resac = db_query("insert into db_acount values($acount,2818,16076,'".AddSlashes(pg_result($resaco,$conresaco,'g05_meta'))."','$this->g05_meta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_processado"]) || $this->g05_processado != "")
           $resac = db_query("insert into db_acount values($acount,2818,16077,'".AddSlashes(pg_result($resaco,$conresaco,'g05_processado'))."','$this->g05_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g05_datalimite"]) || $this->g05_datalimite != "")
           $resac = db_query("insert into db_acount values($acount,2818,16078,'".AddSlashes(pg_result($resaco,$conresaco,'g05_datalimite'))."','$this->g05_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "gestorindicadorregistro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->g05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "gestorindicadorregistro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->g05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
       	 $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->g05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($g05_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($g05_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16069,'$g05_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2818,16069,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16070,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_gestorgrupoindicador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16071,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_gestorindicador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16072,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16073,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16074,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16075,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16076,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_meta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16077,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2818,16078,'','".AddSlashes(pg_result($resaco,$iresaco,'g05_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gestorindicadorregistro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($g05_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " g05_sequencial = $g05_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "gestorindicadorregistro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$g05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "gestorindicadorregistro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$g05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$g05_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:gestorindicadorregistro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $g05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gestorindicadorregistro ";
     $sql .= "      inner join db_config  on  db_config.codigo = gestorindicadorregistro.g05_instit";
     $sql .= "      inner join gestorgrupoindicador  on  gestorgrupoindicador.g03_sequencial = gestorindicadorregistro.g05_gestorgrupoindicador";
     $sql .= "      inner join gestorindicador  on  gestorindicador.g04_sequencial = gestorindicadorregistro.g05_gestorindicador";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_periodicidade  on  db_periodicidade.db84_sequencial = gestorindicador.g04_periodicidade";
     $sql2 = "";
     if($dbwhere==""){
       if($g05_sequencial!=null ){
         $sql2 .= " where gestorindicadorregistro.g05_sequencial = $g05_sequencial "; 
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
   function sql_query_file ( $g05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gestorindicadorregistro ";
     $sql2 = "";
     if($dbwhere==""){
       if($g05_sequencial!=null ){
         $sql2 .= " where gestorindicadorregistro.g05_sequencial = $g05_sequencial "; 
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