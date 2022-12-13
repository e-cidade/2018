<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE veicretirada
class cl_veicretirada { 
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
   var $ve60_codigo = 0; 
   var $ve60_veiculo = 0; 
   var $ve60_veicmotoristas = 0; 
   var $ve60_datasaida_dia = null; 
   var $ve60_datasaida_mes = null; 
   var $ve60_datasaida_ano = null; 
   var $ve60_datasaida = null; 
   var $ve60_horasaida = null; 
   var $ve60_destino = null; 
   var $ve60_coddepto = 0; 
   var $ve60_usuario = 0; 
   var $ve60_data_dia = null; 
   var $ve60_data_mes = null; 
   var $ve60_data_ano = null; 
   var $ve60_data = null; 
   var $ve60_hora = null; 
   var $ve60_medidasaida = 0; 
   var $ve60_passageiro = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve60_codigo = int4 = Código Retirada 
                 ve60_veiculo = int4 = Veiculo 
                 ve60_veicmotoristas = int4 = Motorista 
                 ve60_datasaida = date = Data Retirada 
                 ve60_horasaida = char(5) = Hora Retirada 
                 ve60_destino = text = Destino 
                 ve60_coddepto = int4 = Depart. 
                 ve60_usuario = int4 = Usuário 
                 ve60_data = date = Data 
                 ve60_hora = char(5) = Hora 
                 ve60_medidasaida = float8 = Medida de saída 
                 ve60_passageiro = text = Passageiro 
                 ";
   //funcao construtor da classe 
   function cl_veicretirada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicretirada"); 
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
       $this->ve60_codigo = ($this->ve60_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_codigo"]:$this->ve60_codigo);
       $this->ve60_veiculo = ($this->ve60_veiculo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_veiculo"]:$this->ve60_veiculo);
       $this->ve60_veicmotoristas = ($this->ve60_veicmotoristas == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_veicmotoristas"]:$this->ve60_veicmotoristas);
       if($this->ve60_datasaida == ""){
         $this->ve60_datasaida_dia = ($this->ve60_datasaida_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_datasaida_dia"]:$this->ve60_datasaida_dia);
         $this->ve60_datasaida_mes = ($this->ve60_datasaida_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_datasaida_mes"]:$this->ve60_datasaida_mes);
         $this->ve60_datasaida_ano = ($this->ve60_datasaida_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_datasaida_ano"]:$this->ve60_datasaida_ano);
         if($this->ve60_datasaida_dia != ""){
            $this->ve60_datasaida = $this->ve60_datasaida_ano."-".$this->ve60_datasaida_mes."-".$this->ve60_datasaida_dia;
         }
       }
       $this->ve60_horasaida = ($this->ve60_horasaida == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_horasaida"]:$this->ve60_horasaida);
       $this->ve60_destino = ($this->ve60_destino == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_destino"]:$this->ve60_destino);
       $this->ve60_coddepto = ($this->ve60_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_coddepto"]:$this->ve60_coddepto);
       $this->ve60_usuario = ($this->ve60_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_usuario"]:$this->ve60_usuario);
       if($this->ve60_data == ""){
         $this->ve60_data_dia = ($this->ve60_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_data_dia"]:$this->ve60_data_dia);
         $this->ve60_data_mes = ($this->ve60_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_data_mes"]:$this->ve60_data_mes);
         $this->ve60_data_ano = ($this->ve60_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_data_ano"]:$this->ve60_data_ano);
         if($this->ve60_data_dia != ""){
            $this->ve60_data = $this->ve60_data_ano."-".$this->ve60_data_mes."-".$this->ve60_data_dia;
         }
       }
       $this->ve60_hora = ($this->ve60_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_hora"]:$this->ve60_hora);
       $this->ve60_medidasaida = ($this->ve60_medidasaida == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_medidasaida"]:$this->ve60_medidasaida);
       $this->ve60_passageiro = ($this->ve60_passageiro == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_passageiro"]:$this->ve60_passageiro);
     }else{
       $this->ve60_codigo = ($this->ve60_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve60_codigo"]:$this->ve60_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ve60_codigo){ 
      $this->atualizacampos();
     if($this->ve60_veiculo == null ){ 
       $this->erro_sql = " Campo Veiculo não informado.";
       $this->erro_campo = "ve60_veiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_veicmotoristas == null ){ 
       $this->erro_sql = " Campo Motorista não informado.";
       $this->erro_campo = "ve60_veicmotoristas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_datasaida == null ){ 
       $this->erro_sql = " Campo Data Retirada não informado.";
       $this->erro_campo = "ve60_datasaida_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_horasaida == null ){ 
       $this->erro_sql = " Campo Hora Retirada não informado.";
       $this->erro_campo = "ve60_horasaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_destino == null ){ 
       $this->erro_sql = " Campo Destino não informado.";
       $this->erro_campo = "ve60_destino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_coddepto == null ){ 
       $this->erro_sql = " Campo Depart. não informado.";
       $this->erro_campo = "ve60_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "ve60_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_data == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "ve60_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_hora == null ){ 
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "ve60_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve60_medidasaida == null ){ 
       $this->erro_sql = " Campo Medida de saída não informado.";
       $this->erro_campo = "ve60_medidasaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve60_codigo == "" || $ve60_codigo == null ){
       $result = db_query("select nextval('veicretirada_ve60_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicretirada_ve60_codigo_seq do campo: ve60_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve60_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicretirada_ve60_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve60_codigo)){
         $this->erro_sql = " Campo ve60_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve60_codigo = $ve60_codigo; 
       }
     }
     if(($this->ve60_codigo == null) || ($this->ve60_codigo == "") ){ 
       $this->erro_sql = " Campo ve60_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicretirada(
                                       ve60_codigo 
                                      ,ve60_veiculo 
                                      ,ve60_veicmotoristas 
                                      ,ve60_datasaida 
                                      ,ve60_horasaida 
                                      ,ve60_destino 
                                      ,ve60_coddepto 
                                      ,ve60_usuario 
                                      ,ve60_data 
                                      ,ve60_hora 
                                      ,ve60_medidasaida 
                                      ,ve60_passageiro 
                       )
                values (
                                $this->ve60_codigo 
                               ,$this->ve60_veiculo 
                               ,$this->ve60_veicmotoristas 
                               ,".($this->ve60_datasaida == "null" || $this->ve60_datasaida == ""?"null":"'".$this->ve60_datasaida."'")." 
                               ,'$this->ve60_horasaida' 
                               ,'$this->ve60_destino' 
                               ,$this->ve60_coddepto 
                               ,$this->ve60_usuario 
                               ,".($this->ve60_data == "null" || $this->ve60_data == ""?"null":"'".$this->ve60_data."'")." 
                               ,'$this->ve60_hora' 
                               ,$this->ve60_medidasaida 
                               ,'$this->ve60_passageiro' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Retirada dos Veículos ($this->ve60_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Retirada dos Veículos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Retirada dos Veículos ($this->ve60_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve60_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ve60_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9280,'$this->ve60_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1595,9280,'','".AddSlashes(pg_result($resaco,0,'ve60_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9281,'','".AddSlashes(pg_result($resaco,0,'ve60_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9282,'','".AddSlashes(pg_result($resaco,0,'ve60_veicmotoristas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9283,'','".AddSlashes(pg_result($resaco,0,'ve60_datasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9284,'','".AddSlashes(pg_result($resaco,0,'ve60_horasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9286,'','".AddSlashes(pg_result($resaco,0,'ve60_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9287,'','".AddSlashes(pg_result($resaco,0,'ve60_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9288,'','".AddSlashes(pg_result($resaco,0,'ve60_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9289,'','".AddSlashes(pg_result($resaco,0,'ve60_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,9290,'','".AddSlashes(pg_result($resaco,0,'ve60_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,11081,'','".AddSlashes(pg_result($resaco,0,'ve60_medidasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1595,21349,'','".AddSlashes(pg_result($resaco,0,'ve60_passageiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ve60_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicretirada set ";
     $virgula = "";
     if(trim($this->ve60_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_codigo"])){ 
       $sql  .= $virgula." ve60_codigo = $this->ve60_codigo ";
       $virgula = ",";
       if(trim($this->ve60_codigo) == null ){ 
         $this->erro_sql = " Campo Código Retirada não informado.";
         $this->erro_campo = "ve60_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_veiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_veiculo"])){ 
       $sql  .= $virgula." ve60_veiculo = $this->ve60_veiculo ";
       $virgula = ",";
       if(trim($this->ve60_veiculo) == null ){ 
         $this->erro_sql = " Campo Veiculo não informado.";
         $this->erro_campo = "ve60_veiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_veicmotoristas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_veicmotoristas"])){ 
       $sql  .= $virgula." ve60_veicmotoristas = $this->ve60_veicmotoristas ";
       $virgula = ",";
       if(trim($this->ve60_veicmotoristas) == null ){ 
         $this->erro_sql = " Campo Motorista não informado.";
         $this->erro_campo = "ve60_veicmotoristas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_datasaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_datasaida_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve60_datasaida_dia"] !="") ){ 
       $sql  .= $virgula." ve60_datasaida = '$this->ve60_datasaida' ";
       $virgula = ",";
       if(trim($this->ve60_datasaida) == null ){ 
         $this->erro_sql = " Campo Data Retirada não informado.";
         $this->erro_campo = "ve60_datasaida_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve60_datasaida_dia"])){ 
         $sql  .= $virgula." ve60_datasaida = null ";
         $virgula = ",";
         if(trim($this->ve60_datasaida) == null ){ 
           $this->erro_sql = " Campo Data Retirada não informado.";
           $this->erro_campo = "ve60_datasaida_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve60_horasaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_horasaida"])){ 
       $sql  .= $virgula." ve60_horasaida = '$this->ve60_horasaida' ";
       $virgula = ",";
       if(trim($this->ve60_horasaida) == null ){ 
         $this->erro_sql = " Campo Hora Retirada não informado.";
         $this->erro_campo = "ve60_horasaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_destino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_destino"])){ 
       $sql  .= $virgula." ve60_destino = '$this->ve60_destino' ";
       $virgula = ",";
       if(trim($this->ve60_destino) == null ){ 
         $this->erro_sql = " Campo Destino não informado.";
         $this->erro_campo = "ve60_destino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_coddepto"])){ 
       $sql  .= $virgula." ve60_coddepto = $this->ve60_coddepto ";
       $virgula = ",";
       if(trim($this->ve60_coddepto) == null ){ 
         $this->erro_sql = " Campo Depart. não informado.";
         $this->erro_campo = "ve60_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_usuario"])){ 
       $sql  .= $virgula." ve60_usuario = $this->ve60_usuario ";
       $virgula = ",";
       if(trim($this->ve60_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "ve60_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve60_data_dia"] !="") ){ 
       $sql  .= $virgula." ve60_data = '$this->ve60_data' ";
       $virgula = ",";
       if(trim($this->ve60_data) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "ve60_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve60_data_dia"])){ 
         $sql  .= $virgula." ve60_data = null ";
         $virgula = ",";
         if(trim($this->ve60_data) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "ve60_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve60_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_hora"])){ 
       $sql  .= $virgula." ve60_hora = '$this->ve60_hora' ";
       $virgula = ",";
       if(trim($this->ve60_hora) == null ){ 
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "ve60_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_medidasaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_medidasaida"])){ 
       $sql  .= $virgula." ve60_medidasaida = $this->ve60_medidasaida ";
       $virgula = ",";
       if(trim($this->ve60_medidasaida) == null ){ 
         $this->erro_sql = " Campo Medida de saída não informado.";
         $this->erro_campo = "ve60_medidasaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve60_passageiro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve60_passageiro"])){ 
       $sql  .= $virgula." ve60_passageiro = '$this->ve60_passageiro' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ve60_codigo!=null){
       $sql .= " ve60_codigo = $this->ve60_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ve60_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,9280,'$this->ve60_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_codigo"]) || $this->ve60_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1595,9280,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_codigo'))."','$this->ve60_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_veiculo"]) || $this->ve60_veiculo != "")
             $resac = db_query("insert into db_acount values($acount,1595,9281,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_veiculo'))."','$this->ve60_veiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_veicmotoristas"]) || $this->ve60_veicmotoristas != "")
             $resac = db_query("insert into db_acount values($acount,1595,9282,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_veicmotoristas'))."','$this->ve60_veicmotoristas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_datasaida"]) || $this->ve60_datasaida != "")
             $resac = db_query("insert into db_acount values($acount,1595,9283,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_datasaida'))."','$this->ve60_datasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_horasaida"]) || $this->ve60_horasaida != "")
             $resac = db_query("insert into db_acount values($acount,1595,9284,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_horasaida'))."','$this->ve60_horasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_destino"]) || $this->ve60_destino != "")
             $resac = db_query("insert into db_acount values($acount,1595,9286,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_destino'))."','$this->ve60_destino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_coddepto"]) || $this->ve60_coddepto != "")
             $resac = db_query("insert into db_acount values($acount,1595,9287,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_coddepto'))."','$this->ve60_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_usuario"]) || $this->ve60_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1595,9288,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_usuario'))."','$this->ve60_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_data"]) || $this->ve60_data != "")
             $resac = db_query("insert into db_acount values($acount,1595,9289,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_data'))."','$this->ve60_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_hora"]) || $this->ve60_hora != "")
             $resac = db_query("insert into db_acount values($acount,1595,9290,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_hora'))."','$this->ve60_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_medidasaida"]) || $this->ve60_medidasaida != "")
             $resac = db_query("insert into db_acount values($acount,1595,11081,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_medidasaida'))."','$this->ve60_medidasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve60_passageiro"]) || $this->ve60_passageiro != "")
             $resac = db_query("insert into db_acount values($acount,1595,21349,'".AddSlashes(pg_result($resaco,$conresaco,'ve60_passageiro'))."','$this->ve60_passageiro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retirada dos Veículos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve60_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Retirada dos Veículos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve60_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve60_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ve60_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ve60_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,9280,'$ve60_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1595,9280,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9281,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9282,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_veicmotoristas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9283,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_datasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9284,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_horasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9286,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9287,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9288,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9289,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,9290,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,11081,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_medidasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1595,21349,'','".AddSlashes(pg_result($resaco,$iresaco,'ve60_passageiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from veicretirada
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ve60_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ve60_codigo = $ve60_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retirada dos Veículos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve60_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Retirada dos Veículos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve60_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve60_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:veicretirada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ve60_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from veicretirada ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicretirada.ve60_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = veicretirada.ve60_coddepto";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicretirada.ve60_veiculo";
     $sql .= "      inner join veiccentral    on veiccentral.ve40_veiculos = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcentral on veiccadcentral.ve36_sequencial = veiccentral.ve40_veiccadcentral";
     $sql .= "      inner join veicmotoristas  on  veicmotoristas.ve05_codigo = veicretirada.ve60_veicmotoristas";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo  on  veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      left  join veiccadmarca  on  veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on  veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      left  join veiccadcor  on  veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade  on  veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiculoscomb on veiculoscomb.ve06_veiculos = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcomb  on  veiccadcomb.ve26_codigo = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced  on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia  on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as a on   a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh  as b on   b.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      inner join veiccadmotoristasit  on  veiccadmotoristasit.ve33_codigo = veicmotoristas.ve05_veiccadmotoristasit";
     $sql .= "      left  join veiccadcentraldepart  on veiccadcentraldepart.ve37_veiccadcentral = veiccadcentral.ve36_sequencial";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve60_codigo)) {
         $sql2 .= " where veicretirada.ve60_codigo = $ve60_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($ve60_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from veicretirada ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve60_codigo)){
         $sql2 .= " where veicretirada.ve60_codigo = $ve60_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   function sql_query_devol ( $ve60_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicretirada ";
     $sql .= "      inner join db_usuarios    on db_usuarios.id_usuario = veicretirada.ve60_usuario";
     $sql .= "      inner join db_depart      on db_depart.coddepto     = veicretirada.ve60_coddepto";
     $sql .= "      inner join veiculos       on veiculos.ve01_codigo   = veicretirada.ve60_veiculo";
     $sql .= "      inner join veiccentral    on veiccentral.ve40_veiculos      = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcentral on veiccadcentral.ve36_sequencial = veiccentral.ve40_veiccadcentral";
     $sql .= "      inner join veicmotoristas on veicmotoristas.ve05_codigo  = veicretirada.ve60_veicmotoristas";
     $sql .= "      inner join veiccadtipo     on veiccadtipo.ve20_codigo    = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca    on veiccadmarca.ve21_codigo   = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo   on veiccadmodelo.ve22_codigo  = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor      on veiccadcor.ve23_codigo     = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiculoscomb    on veiculoscomb.ve06_veiculos = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcomb     on veiccadcomb.ve26_codigo     = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiccadcategcnh on veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join cgm             on cgm.z01_numcgm              = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh as a on a.ve30_codigo  = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      left join veicdevolucao on veicretirada.ve60_codigo = veicdevolucao.ve61_veicretirada ";
     $sql .= "      left  join veiccadcentraldepart  on veiccadcentraldepart.ve37_veiccadcentral = veiccadcentral.ve36_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($ve60_codigo!=null ){
         $sql2 .= " where veicretirada.ve60_codigo = $ve60_codigo "; 
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
   function sql_query_info ( $ve60_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicretirada ";
     $sql .= "      inner join db_usuarios     on db_usuarios.id_usuario      = veicretirada.ve60_usuario";
     $sql .= "      inner join db_depart       on db_depart.coddepto          = veicretirada.ve60_coddepto";
     $sql .= "      inner join veiculos        on veiculos.ve01_codigo        = veicretirada.ve60_veiculo";
     $sql .= "      inner join veicmotoristas  on veicmotoristas.ve05_codigo  = veicretirada.ve60_veicmotoristas";
     $sql .= "      inner join veiccadtipo     on veiccadtipo.ve20_codigo     = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca    on veiccadmarca.ve21_codigo    = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo   on veiccadmodelo.ve22_codigo   = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor      on veiccadcor.ve23_codigo      = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiculoscomb    on veiculoscomb.ve06_veiculos  = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcomb     on veiccadcomb.ve26_codigo     = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiccadcategcnh on veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join cgm             on cgm.z01_numcgm      = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh as a on  a.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "    left join veicdevolucao     on veicretirada.ve60_codigo = veicdevolucao.ve61_veicretirada ";
     $sql .= "		left join veicabastretirada on ve73_veicretirada        = ve60_codigo";
     $sql .= "		left join veicmanutretirada on ve65_veicretirada        = ve60_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ve60_codigo!=null ){
         $sql2 .= " where veicretirada.ve60_codigo = $ve60_codigo "; 
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
