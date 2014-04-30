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

//MODULO: contabilidade
//CLASSE DA ENTIDADE escrituraprovisao
class cl_escrituraprovisao { 
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
   var $c102_sequencial = 0; 
   var $c102_usuario = 0; 
   var $c102_instit = 0; 
   var $c102_mes = 0; 
   var $c102_ano = 0; 
   var $c102_data_dia = null; 
   var $c102_data_mes = null; 
   var $c102_data_ano = null; 
   var $c102_data = null; 
   var $c102_tipoprovisao = 0; 
   var $c102_processado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c102_sequencial = int4 = Sequencial 
                 c102_usuario = int4 = Cod. Usuário 
                 c102_instit = int4 = Cod. Instituição 
                 c102_mes = int4 = Mês 
                 c102_ano = int4 = Ano 
                 c102_data = date = Data 
                 c102_tipoprovisao = int4 = Tipo de Provisão 
                 c102_processado = bool = Processado 
                 ";
   //funcao construtor da classe 
   function cl_escrituraprovisao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("escrituraprovisao"); 
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
       $this->c102_sequencial = ($this->c102_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_sequencial"]:$this->c102_sequencial);
       $this->c102_usuario = ($this->c102_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_usuario"]:$this->c102_usuario);
       $this->c102_instit = ($this->c102_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_instit"]:$this->c102_instit);
       $this->c102_mes = ($this->c102_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_mes"]:$this->c102_mes);
       $this->c102_ano = ($this->c102_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_ano"]:$this->c102_ano);
       if($this->c102_data == ""){
         $this->c102_data_dia = ($this->c102_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_data_dia"]:$this->c102_data_dia);
         $this->c102_data_mes = ($this->c102_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_data_mes"]:$this->c102_data_mes);
         $this->c102_data_ano = ($this->c102_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_data_ano"]:$this->c102_data_ano);
         if($this->c102_data_dia != ""){
            $this->c102_data = $this->c102_data_ano."-".$this->c102_data_mes."-".$this->c102_data_dia;
         }
       }
       $this->c102_tipoprovisao = ($this->c102_tipoprovisao == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_tipoprovisao"]:$this->c102_tipoprovisao);
       $this->c102_processado = ($this->c102_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["c102_processado"]:$this->c102_processado);
     }else{
       $this->c102_sequencial = ($this->c102_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c102_sequencial"]:$this->c102_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c102_sequencial){ 
      $this->atualizacampos();
     if($this->c102_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "c102_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c102_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "c102_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c102_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "c102_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c102_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c102_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c102_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c102_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c102_tipoprovisao == null ){ 
       $this->erro_sql = " Campo Tipo de Provisão nao Informado.";
       $this->erro_campo = "c102_tipoprovisao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c102_processado == null ){ 
       $this->erro_sql = " Campo Processado nao Informado.";
       $this->erro_campo = "c102_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c102_sequencial == "" || $c102_sequencial == null ){
       $result = db_query("select nextval('escrituraprovisao_c102_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: escrituraprovisao_c102_sequencial_seq do campo: c102_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c102_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from escrituraprovisao_c102_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c102_sequencial)){
         $this->erro_sql = " Campo c102_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c102_sequencial = $c102_sequencial; 
       }
     }
     if(($this->c102_sequencial == null) || ($this->c102_sequencial == "") ){ 
       $this->erro_sql = " Campo c102_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escrituraprovisao(
                                       c102_sequencial 
                                      ,c102_usuario 
                                      ,c102_instit 
                                      ,c102_mes 
                                      ,c102_ano 
                                      ,c102_data 
                                      ,c102_tipoprovisao 
                                      ,c102_processado 
                       )
                values (
                                $this->c102_sequencial 
                               ,$this->c102_usuario 
                               ,$this->c102_instit 
                               ,$this->c102_mes 
                               ,$this->c102_ano 
                               ,".($this->c102_data == "null" || $this->c102_data == ""?"null":"'".$this->c102_data."'")." 
                               ,$this->c102_tipoprovisao 
                               ,'$this->c102_processado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Escrituras de provisão ($this->c102_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Escrituras de provisão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Escrituras de provisão ($this->c102_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c102_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c102_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19463,'$this->c102_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3455,19463,'','".AddSlashes(pg_result($resaco,0,'c102_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3455,19465,'','".AddSlashes(pg_result($resaco,0,'c102_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3455,19466,'','".AddSlashes(pg_result($resaco,0,'c102_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3455,19512,'','".AddSlashes(pg_result($resaco,0,'c102_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3455,19513,'','".AddSlashes(pg_result($resaco,0,'c102_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3455,19464,'','".AddSlashes(pg_result($resaco,0,'c102_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3455,19467,'','".AddSlashes(pg_result($resaco,0,'c102_tipoprovisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3455,19511,'','".AddSlashes(pg_result($resaco,0,'c102_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c102_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update escrituraprovisao set ";
     $virgula = "";
     if(trim($this->c102_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c102_sequencial"])){ 
       $sql  .= $virgula." c102_sequencial = $this->c102_sequencial ";
       $virgula = ",";
       if(trim($this->c102_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "c102_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c102_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c102_usuario"])){ 
       $sql  .= $virgula." c102_usuario = $this->c102_usuario ";
       $virgula = ",";
       if(trim($this->c102_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "c102_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c102_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c102_instit"])){ 
       $sql  .= $virgula." c102_instit = $this->c102_instit ";
       $virgula = ",";
       if(trim($this->c102_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "c102_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c102_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c102_mes"])){ 
       $sql  .= $virgula." c102_mes = $this->c102_mes ";
       $virgula = ",";
       if(trim($this->c102_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "c102_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c102_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c102_ano"])){ 
       $sql  .= $virgula." c102_ano = $this->c102_ano ";
       $virgula = ",";
       if(trim($this->c102_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c102_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c102_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c102_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c102_data_dia"] !="") ){ 
       $sql  .= $virgula." c102_data = '$this->c102_data' ";
       $virgula = ",";
       if(trim($this->c102_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c102_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c102_data_dia"])){ 
         $sql  .= $virgula." c102_data = null ";
         $virgula = ",";
         if(trim($this->c102_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c102_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c102_tipoprovisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c102_tipoprovisao"])){ 
       $sql  .= $virgula." c102_tipoprovisao = $this->c102_tipoprovisao ";
       $virgula = ",";
       if(trim($this->c102_tipoprovisao) == null ){ 
         $this->erro_sql = " Campo Tipo de Provisão nao Informado.";
         $this->erro_campo = "c102_tipoprovisao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c102_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c102_processado"])){ 
       $sql  .= $virgula." c102_processado = '$this->c102_processado' ";
       $virgula = ",";
       if(trim($this->c102_processado) == null ){ 
         $this->erro_sql = " Campo Processado nao Informado.";
         $this->erro_campo = "c102_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c102_sequencial!=null){
       $sql .= " c102_sequencial = $this->c102_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c102_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19463,'$this->c102_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c102_sequencial"]) || $this->c102_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3455,19463,'".AddSlashes(pg_result($resaco,$conresaco,'c102_sequencial'))."','$this->c102_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c102_usuario"]) || $this->c102_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3455,19465,'".AddSlashes(pg_result($resaco,$conresaco,'c102_usuario'))."','$this->c102_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c102_instit"]) || $this->c102_instit != "")
           $resac = db_query("insert into db_acount values($acount,3455,19466,'".AddSlashes(pg_result($resaco,$conresaco,'c102_instit'))."','$this->c102_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c102_mes"]) || $this->c102_mes != "")
           $resac = db_query("insert into db_acount values($acount,3455,19512,'".AddSlashes(pg_result($resaco,$conresaco,'c102_mes'))."','$this->c102_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c102_ano"]) || $this->c102_ano != "")
           $resac = db_query("insert into db_acount values($acount,3455,19513,'".AddSlashes(pg_result($resaco,$conresaco,'c102_ano'))."','$this->c102_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c102_data"]) || $this->c102_data != "")
           $resac = db_query("insert into db_acount values($acount,3455,19464,'".AddSlashes(pg_result($resaco,$conresaco,'c102_data'))."','$this->c102_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c102_tipoprovisao"]) || $this->c102_tipoprovisao != "")
           $resac = db_query("insert into db_acount values($acount,3455,19467,'".AddSlashes(pg_result($resaco,$conresaco,'c102_tipoprovisao'))."','$this->c102_tipoprovisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c102_processado"]) || $this->c102_processado != "")
           $resac = db_query("insert into db_acount values($acount,3455,19511,'".AddSlashes(pg_result($resaco,$conresaco,'c102_processado'))."','$this->c102_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Escrituras de provisão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c102_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Escrituras de provisão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c102_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c102_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19463,'$c102_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3455,19463,'','".AddSlashes(pg_result($resaco,$iresaco,'c102_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3455,19465,'','".AddSlashes(pg_result($resaco,$iresaco,'c102_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3455,19466,'','".AddSlashes(pg_result($resaco,$iresaco,'c102_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3455,19512,'','".AddSlashes(pg_result($resaco,$iresaco,'c102_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3455,19513,'','".AddSlashes(pg_result($resaco,$iresaco,'c102_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3455,19464,'','".AddSlashes(pg_result($resaco,$iresaco,'c102_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3455,19467,'','".AddSlashes(pg_result($resaco,$iresaco,'c102_tipoprovisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3455,19511,'','".AddSlashes(pg_result($resaco,$iresaco,'c102_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from escrituraprovisao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c102_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c102_sequencial = $c102_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Escrituras de provisão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c102_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Escrituras de provisão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c102_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:escrituraprovisao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c102_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from escrituraprovisao ";
     $sql .= "      inner join db_config  on  db_config.codigo = escrituraprovisao.c102_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = escrituraprovisao.c102_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($c102_sequencial!=null ){
         $sql2 .= " where escrituraprovisao.c102_sequencial = $c102_sequencial "; 
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
   function sql_query_file ( $c102_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from escrituraprovisao ";
     $sql2 = "";
     if($dbwhere==""){
       if($c102_sequencial!=null ){
         $sql2 .= " where escrituraprovisao.c102_sequencial = $c102_sequencial "; 
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