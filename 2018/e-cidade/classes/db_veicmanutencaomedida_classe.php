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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicmanutencaomedida
class cl_veicmanutencaomedida { 
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
   var $ve66_sequencial = 0; 
   var $ve66_veiculo = 0; 
   var $ve66_medidaanterior = 0; 
   var $ve66_data_dia = null; 
   var $ve66_data_mes = null; 
   var $ve66_data_ano = null; 
   var $ve66_data = null; 
   var $ve66_hora = null; 
   var $ve66_usuario = 0; 
   var $ve66_motivo = null; 
   var $ve66_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve66_sequencial = int4 = Código Manutenção: 
                 ve66_veiculo = int4 = Veículo 
                 ve66_medidaanterior = float8 = Medida Anterior 
                 ve66_data = date = Data 
                 ve66_hora = char(5) = Horário 
                 ve66_usuario = int4 = Usuário 
                 ve66_motivo = text = Motivo 
                 ve66_ativo = bool = Registro Ativo 
                 ";
   //funcao construtor da classe 
   function cl_veicmanutencaomedida() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmanutencaomedida"); 
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
       $this->ve66_sequencial = ($this->ve66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_sequencial"]:$this->ve66_sequencial);
       $this->ve66_veiculo = ($this->ve66_veiculo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_veiculo"]:$this->ve66_veiculo);
       $this->ve66_medidaanterior = ($this->ve66_medidaanterior == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_medidaanterior"]:$this->ve66_medidaanterior);
       if($this->ve66_data == ""){
         $this->ve66_data_dia = ($this->ve66_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_data_dia"]:$this->ve66_data_dia);
         $this->ve66_data_mes = ($this->ve66_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_data_mes"]:$this->ve66_data_mes);
         $this->ve66_data_ano = ($this->ve66_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_data_ano"]:$this->ve66_data_ano);
         if($this->ve66_data_dia != ""){
            $this->ve66_data = $this->ve66_data_ano."-".$this->ve66_data_mes."-".$this->ve66_data_dia;
         }
       }
       $this->ve66_hora = ($this->ve66_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_hora"]:$this->ve66_hora);
       $this->ve66_usuario = ($this->ve66_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_usuario"]:$this->ve66_usuario);
       $this->ve66_motivo = ($this->ve66_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_motivo"]:$this->ve66_motivo);
       $this->ve66_ativo = ($this->ve66_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ve66_ativo"]:$this->ve66_ativo);
     }else{
       $this->ve66_sequencial = ($this->ve66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve66_sequencial"]:$this->ve66_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ve66_sequencial){ 
      $this->atualizacampos();
     if($this->ve66_veiculo == null ){ 
       $this->erro_sql = " Campo Veículo nao Informado.";
       $this->erro_campo = "ve66_veiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve66_medidaanterior == null ){ 
       $this->erro_sql = " Campo Medida Anterior nao Informado.";
       $this->erro_campo = "ve66_medidaanterior";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve66_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ve66_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve66_hora == null ){ 
       $this->erro_sql = " Campo Horário nao Informado.";
       $this->erro_campo = "ve66_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve66_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ve66_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve66_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "ve66_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve66_ativo == null ){ 
       $this->erro_sql = " Campo Registro Ativo nao Informado.";
       $this->erro_campo = "ve66_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve66_sequencial == "" || $ve66_sequencial == null ){
       $result = db_query("select nextval('veicmanutencaomedida_ve66_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmanutencaomedida_ve66_sequencial_seq do campo: ve66_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve66_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicmanutencaomedida_ve66_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve66_sequencial)){
         $this->erro_sql = " Campo ve66_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve66_sequencial = $ve66_sequencial; 
       }
     }
     if(($this->ve66_sequencial == null) || ($this->ve66_sequencial == "") ){ 
       $this->erro_sql = " Campo ve66_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmanutencaomedida(
                                       ve66_sequencial 
                                      ,ve66_veiculo 
                                      ,ve66_medidaanterior 
                                      ,ve66_data 
                                      ,ve66_hora 
                                      ,ve66_usuario 
                                      ,ve66_motivo 
                                      ,ve66_ativo 
                       )
                values (
                                $this->ve66_sequencial 
                               ,$this->ve66_veiculo 
                               ,$this->ve66_medidaanterior 
                               ,".($this->ve66_data == "null" || $this->ve66_data == ""?"null":"'".$this->ve66_data."'")." 
                               ,'$this->ve66_hora' 
                               ,$this->ve66_usuario 
                               ,'$this->ve66_motivo' 
                               ,'$this->ve66_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Manutenção de Horimetro/Horometro ($this->ve66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Manutenção de Horimetro/Horometro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Manutenção de Horimetro/Horometro ($this->ve66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve66_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve66_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18288,'$this->ve66_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3235,18288,'','".AddSlashes(pg_result($resaco,0,'ve66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3235,18289,'','".AddSlashes(pg_result($resaco,0,'ve66_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3235,18290,'','".AddSlashes(pg_result($resaco,0,'ve66_medidaanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3235,18291,'','".AddSlashes(pg_result($resaco,0,'ve66_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3235,18295,'','".AddSlashes(pg_result($resaco,0,'ve66_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3235,18292,'','".AddSlashes(pg_result($resaco,0,'ve66_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3235,18293,'','".AddSlashes(pg_result($resaco,0,'ve66_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3235,18294,'','".AddSlashes(pg_result($resaco,0,'ve66_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve66_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update veicmanutencaomedida set ";
     $virgula = "";
     if(trim($this->ve66_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_sequencial"])){ 
       $sql  .= $virgula." ve66_sequencial = $this->ve66_sequencial ";
       $virgula = ",";
       if(trim($this->ve66_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Manutenção: nao Informado.";
         $this->erro_campo = "ve66_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve66_veiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_veiculo"])){ 
       $sql  .= $virgula." ve66_veiculo = $this->ve66_veiculo ";
       $virgula = ",";
       if(trim($this->ve66_veiculo) == null ){ 
         $this->erro_sql = " Campo Veículo nao Informado.";
         $this->erro_campo = "ve66_veiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve66_medidaanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_medidaanterior"])){ 
       $sql  .= $virgula." ve66_medidaanterior = $this->ve66_medidaanterior ";
       $virgula = ",";
       if(trim($this->ve66_medidaanterior) == null ){ 
         $this->erro_sql = " Campo Medida Anterior nao Informado.";
         $this->erro_campo = "ve66_medidaanterior";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve66_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve66_data_dia"] !="") ){ 
       $sql  .= $virgula." ve66_data = '$this->ve66_data' ";
       $virgula = ",";
       if(trim($this->ve66_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ve66_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_data_dia"])){ 
         $sql  .= $virgula." ve66_data = null ";
         $virgula = ",";
         if(trim($this->ve66_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ve66_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve66_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_hora"])){ 
       $sql  .= $virgula." ve66_hora = '$this->ve66_hora' ";
       $virgula = ",";
       if(trim($this->ve66_hora) == null ){ 
         $this->erro_sql = " Campo Horário nao Informado.";
         $this->erro_campo = "ve66_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve66_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_usuario"])){ 
       $sql  .= $virgula." ve66_usuario = $this->ve66_usuario ";
       $virgula = ",";
       if(trim($this->ve66_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ve66_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve66_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_motivo"])){ 
       $sql  .= $virgula." ve66_motivo = '$this->ve66_motivo' ";
       $virgula = ",";
       if(trim($this->ve66_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "ve66_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve66_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve66_ativo"])){ 
       $sql  .= $virgula." ve66_ativo = '$this->ve66_ativo' ";
       $virgula = ",";
       if(trim($this->ve66_ativo) == null ){ 
         $this->erro_sql = " Campo Registro Ativo nao Informado.";
         $this->erro_campo = "ve66_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve66_sequencial!=null){
       $sql .= " ve66_sequencial = $this->ve66_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve66_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18288,'$this->ve66_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_sequencial"]) || $this->ve66_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3235,18288,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_sequencial'))."','$this->ve66_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_veiculo"]) || $this->ve66_veiculo != "")
           $resac = db_query("insert into db_acount values($acount,3235,18289,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_veiculo'))."','$this->ve66_veiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_medidaanterior"]) || $this->ve66_medidaanterior != "")
           $resac = db_query("insert into db_acount values($acount,3235,18290,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_medidaanterior'))."','$this->ve66_medidaanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_data"]) || $this->ve66_data != "")
           $resac = db_query("insert into db_acount values($acount,3235,18291,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_data'))."','$this->ve66_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_hora"]) || $this->ve66_hora != "")
           $resac = db_query("insert into db_acount values($acount,3235,18295,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_hora'))."','$this->ve66_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_usuario"]) || $this->ve66_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3235,18292,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_usuario'))."','$this->ve66_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_motivo"]) || $this->ve66_motivo != "")
           $resac = db_query("insert into db_acount values($acount,3235,18293,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_motivo'))."','$this->ve66_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve66_ativo"]) || $this->ve66_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3235,18294,'".AddSlashes(pg_result($resaco,$conresaco,'ve66_ativo'))."','$this->ve66_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Manutenção de Horimetro/Horometro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Manutenção de Horimetro/Horometro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve66_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve66_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18288,'$ve66_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3235,18288,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3235,18289,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3235,18290,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_medidaanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3235,18291,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3235,18295,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3235,18292,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3235,18293,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3235,18294,'','".AddSlashes(pg_result($resaco,$iresaco,'ve66_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicmanutencaomedida
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve66_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve66_sequencial = $ve66_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Manutenção de Horimetro/Horometro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Manutenção de Horimetro/Horometro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve66_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicmanutencaomedida";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ve66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutencaomedida ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicmanutencaomedida.ve66_usuario";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicmanutencaomedida.ve66_veiculo";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo  on  veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca  on  veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on  veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor  on  veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade  on  veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced  on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia  on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as a on   a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      inner join veictipoabast  on  veictipoabast.ve07_sequencial = veiculos.ve01_veictipoabast";
     $sql2 = "";
     if($dbwhere==""){
       if($ve66_sequencial!=null ){
         $sql2 .= " where veicmanutencaomedida.ve66_sequencial = $ve66_sequencial "; 
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
   function sql_query_file ( $ve66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutencaomedida ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve66_sequencial!=null ){
         $sql2 .= " where veicmanutencaomedida.ve66_sequencial = $ve66_sequencial "; 
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
   function sql_query_manutencoes ( $ve66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutencaomedida ";
     $sql .= "      inner join db_usuarios as usuario_medida    on usuario_medida.id_usuario = veicmanutencaomedida.ve66_usuario";
     $sql .= "      left join veicmanutencaomedidacancela       on veicmanutencaomedidacancela.ve67_veicmanutencaomedida = veicmanutencaomedida.ve66_sequencial";
     $sql .= "      left join db_usuarios as usuario_medidacanc on usuario_medidacanc.id_usuario = veicmanutencaomedidacancela.ve67_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ve66_sequencial!=null ){
         $sql2 .= " where veicmanutencaomedida.ve66_sequencial = $ve66_sequencial "; 
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
   function sql_query_movimentos ($ve66_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= "from veicmanutencaomedida                                      ";
     $sql .= "     inner join veiculos      on veiculos.ve01_codigo            = veicmanutencaomedida.ve66_veiculo ";
     $sql .= "     left  join veicabast     on veicabast.ve70_veiculos         = veiculos.ve01_codigo  ";
     $sql .= "     left  join veicmanut     on veicmanut.ve62_veiculos         = veiculos.ve01_codigo  ";
     $sql .= "     left  join veicretirada  on veicretirada.ve60_veiculo       = veiculos.ve01_codigo  ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve66_sequencial!=null ){
         $sql2 .= " where veicmanutencaomedida.ve66_sequencial = $ve66_sequencial "; 
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