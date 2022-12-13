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

//MODULO: ITBI
//CLASSE DA ENTIDADE itbi
class cl_itbi { 
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
   var $it01_guia = 0; 
   var $it01_data_dia = null; 
   var $it01_data_mes = null; 
   var $it01_data_ano = null; 
   var $it01_data = null; 
   var $it01_hora = null; 
   var $it01_tipotransacao = 0; 
   var $it01_areaterreno = 0; 
   var $it01_areaedificada = 0; 
   var $it01_obs = null; 
   var $it01_valortransacao = 0; 
   var $it01_areatrans = 0; 
   var $it01_mail = null; 
   var $it01_finalizado = 'f'; 
   var $it01_origem = 0; 
   var $it01_id_usuario = 0; 
   var $it01_coddepto = 0; 
   var $it01_valorterreno = 0; 
   var $it01_valorconstr = 0; 
   var $it01_envia = 'f'; 
   var $it01_percentualareatransmitida = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it01_guia = int8 = Código da ITBI 
                 it01_data = date = Data da solicitação 
                 it01_hora = varchar(5) = Hora da solicitação 
                 it01_tipotransacao = int8 = Tipo de Transação 
                 it01_areaterreno = float8 = Àrea do terreno 
                 it01_areaedificada = float8 = Área edificada 
                 it01_obs = text = Observações 
                 it01_valortransacao = float8 = Valor da transação 
                 it01_areatrans = float8 = Área transmitida do terreno 
                 it01_mail = varchar(50) = E-mail de contato 
                 it01_finalizado = bool = Finalizado 
                 it01_origem = int4 = Origem 
                 it01_id_usuario = int4 = Usuário 
                 it01_coddepto = int4 = Depto. 
                 it01_valorterreno = float8 = Valor Terreno 
                 it01_valorconstr = float8 = Valor Construção 
                 it01_envia = bool = Enviado para Liberação 
                 it01_percentualareatransmitida = float8 = Percentual Área Transmitida 
                 ";
   //funcao construtor da classe 
   function cl_itbi() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbi"); 
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
       $this->it01_guia = ($this->it01_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_guia"]:$this->it01_guia);
       if($this->it01_data == ""){
         $this->it01_data_dia = ($this->it01_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_data_dia"]:$this->it01_data_dia);
         $this->it01_data_mes = ($this->it01_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_data_mes"]:$this->it01_data_mes);
         $this->it01_data_ano = ($this->it01_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_data_ano"]:$this->it01_data_ano);
         if($this->it01_data_dia != ""){
            $this->it01_data = $this->it01_data_ano."-".$this->it01_data_mes."-".$this->it01_data_dia;
         }
       }
       $this->it01_hora = ($this->it01_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_hora"]:$this->it01_hora);
       $this->it01_tipotransacao = ($this->it01_tipotransacao == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_tipotransacao"]:$this->it01_tipotransacao);
       $this->it01_areaterreno = ($this->it01_areaterreno == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_areaterreno"]:$this->it01_areaterreno);
       $this->it01_areaedificada = ($this->it01_areaedificada == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_areaedificada"]:$this->it01_areaedificada);
       $this->it01_obs = ($this->it01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_obs"]:$this->it01_obs);
       $this->it01_valortransacao = ($this->it01_valortransacao == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_valortransacao"]:$this->it01_valortransacao);
       $this->it01_areatrans = ($this->it01_areatrans == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_areatrans"]:$this->it01_areatrans);
       $this->it01_mail = ($this->it01_mail == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_mail"]:$this->it01_mail);
       $this->it01_finalizado = ($this->it01_finalizado == "f"?@$GLOBALS["HTTP_POST_VARS"]["it01_finalizado"]:$this->it01_finalizado);
       $this->it01_origem = ($this->it01_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_origem"]:$this->it01_origem);
       $this->it01_id_usuario = ($this->it01_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_id_usuario"]:$this->it01_id_usuario);
       $this->it01_coddepto = ($this->it01_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_coddepto"]:$this->it01_coddepto);
       $this->it01_valorterreno = ($this->it01_valorterreno == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_valorterreno"]:$this->it01_valorterreno);
       $this->it01_valorconstr = ($this->it01_valorconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_valorconstr"]:$this->it01_valorconstr);
       $this->it01_envia = ($this->it01_envia == "f"?@$GLOBALS["HTTP_POST_VARS"]["it01_envia"]:$this->it01_envia);
       $this->it01_percentualareatransmitida = ($this->it01_percentualareatransmitida == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_percentualareatransmitida"]:$this->it01_percentualareatransmitida);
     }else{
       $this->it01_guia = ($this->it01_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it01_guia"]:$this->it01_guia);
     }
   }
   // funcao para inclusao
   function incluir ($it01_guia){ 
      $this->atualizacampos();
     if($this->it01_data == null ){ 
       $this->erro_sql = " Campo Data da solicitação não informado.";
       $this->erro_campo = "it01_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_hora == null ){ 
       $this->erro_sql = " Campo Hora da solicitação não informado.";
       $this->erro_campo = "it01_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_tipotransacao == null ){ 
       $this->erro_sql = " Campo Tipo de Transação não informado.";
       $this->erro_campo = "it01_tipotransacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_areaterreno == null ){ 
       $this->erro_sql = " Campo Àrea do terreno não informado.";
       $this->erro_campo = "it01_areaterreno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_areaedificada == null ){ 
       $this->erro_sql = " Campo Área edificada não informado.";
       $this->erro_campo = "it01_areaedificada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_valortransacao == null ){ 
       $this->erro_sql = " Campo Valor da transação não informado.";
       $this->erro_campo = "it01_valortransacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_areatrans == null ){ 
       $this->erro_sql = " Campo Área transmitida do terreno não informado.";
       $this->erro_campo = "it01_areatrans";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_finalizado == null ){ 
       $this->it01_finalizado = "f";
     }
     if($this->it01_origem == null ){ 
       $this->erro_sql = " Campo Origem não informado.";
       $this->erro_campo = "it01_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "it01_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_coddepto == null ){ 
       $this->erro_sql = " Campo Depto. não informado.";
       $this->erro_campo = "it01_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_valorterreno == null ){ 
       $this->it01_valorterreno = "0";
     }
     if($this->it01_valorconstr == null ){ 
       $this->it01_valorconstr = "0";
     }
     if($this->it01_envia == null ){ 
       $this->erro_sql = " Campo Enviado para Liberação não informado.";
       $this->erro_campo = "it01_envia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it01_percentualareatransmitida == null || trim($this->it01_percentualareatransmitida) == ""){ 
       
       $this->it01_percentualareatransmitida = 0;    
     }
     if($it01_guia == "" || $it01_guia == null ){
       $result = db_query("select nextval('itbi_it01_guia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbi_it01_guia_seq do campo: it01_guia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it01_guia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbi_it01_guia_seq");
       if(($result != false) && (pg_result($result,0,0) < $it01_guia)){
         $this->erro_sql = " Campo it01_guia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it01_guia = $it01_guia; 
       }
     }
     if(($this->it01_guia == null) || ($this->it01_guia == "") ){ 
       $this->erro_sql = " Campo it01_guia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbi(
                                       it01_guia 
                                      ,it01_data 
                                      ,it01_hora 
                                      ,it01_tipotransacao 
                                      ,it01_areaterreno 
                                      ,it01_areaedificada 
                                      ,it01_obs 
                                      ,it01_valortransacao 
                                      ,it01_areatrans 
                                      ,it01_mail 
                                      ,it01_finalizado 
                                      ,it01_origem 
                                      ,it01_id_usuario 
                                      ,it01_coddepto 
                                      ,it01_valorterreno 
                                      ,it01_valorconstr 
                                      ,it01_envia 
                                      ,it01_percentualareatransmitida 
                       )
                values (
                                $this->it01_guia 
                               ,".($this->it01_data == "null" || $this->it01_data == ""?"null":"'".$this->it01_data."'")." 
                               ,'$this->it01_hora' 
                               ,$this->it01_tipotransacao 
                               ,$this->it01_areaterreno 
                               ,$this->it01_areaedificada 
                               ,'$this->it01_obs' 
                               ,$this->it01_valortransacao 
                               ,$this->it01_areatrans 
                               ,'$this->it01_mail' 
                               ,'$this->it01_finalizado' 
                               ,$this->it01_origem 
                               ,$this->it01_id_usuario 
                               ,$this->it01_coddepto 
                               ,$this->it01_valorterreno 
                               ,$this->it01_valorconstr 
                               ,'$this->it01_envia' 
                               ,$this->it01_percentualareatransmitida 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ITBI ($this->it01_guia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ITBI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ITBI ($this->it01_guia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it01_guia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it01_guia  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5395,'$this->it01_guia','I')");
         $resac = db_query("insert into db_acount values($acount,792,5395,'','".AddSlashes(pg_result($resaco,0,'it01_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5393,'','".AddSlashes(pg_result($resaco,0,'it01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5394,'','".AddSlashes(pg_result($resaco,0,'it01_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5398,'','".AddSlashes(pg_result($resaco,0,'it01_tipotransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5389,'','".AddSlashes(pg_result($resaco,0,'it01_areaterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5390,'','".AddSlashes(pg_result($resaco,0,'it01_areaedificada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5392,'','".AddSlashes(pg_result($resaco,0,'it01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5402,'','".AddSlashes(pg_result($resaco,0,'it01_valortransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5411,'','".AddSlashes(pg_result($resaco,0,'it01_areatrans'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,5415,'','".AddSlashes(pg_result($resaco,0,'it01_mail'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,9630,'','".AddSlashes(pg_result($resaco,0,'it01_finalizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,13523,'','".AddSlashes(pg_result($resaco,0,'it01_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,13524,'','".AddSlashes(pg_result($resaco,0,'it01_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,13525,'','".AddSlashes(pg_result($resaco,0,'it01_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,13541,'','".AddSlashes(pg_result($resaco,0,'it01_valorterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,13542,'','".AddSlashes(pg_result($resaco,0,'it01_valorconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,15549,'','".AddSlashes(pg_result($resaco,0,'it01_envia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,792,20658,'','".AddSlashes(pg_result($resaco,0,'it01_percentualareatransmitida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it01_guia=null) { 
      $this->atualizacampos();
     $sql = " update itbi set ";
     $virgula = "";
     if(trim($this->it01_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_guia"])){ 
       $sql  .= $virgula." it01_guia = $this->it01_guia ";
       $virgula = ",";
       if(trim($this->it01_guia) == null ){ 
         $this->erro_sql = " Campo Código da ITBI não informado.";
         $this->erro_campo = "it01_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["it01_data_dia"] !="") ){ 
       $sql  .= $virgula." it01_data = '$this->it01_data' ";
       $virgula = ",";
       if(trim($this->it01_data) == null ){ 
         $this->erro_sql = " Campo Data da solicitação não informado.";
         $this->erro_campo = "it01_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["it01_data_dia"])){ 
         $sql  .= $virgula." it01_data = null ";
         $virgula = ",";
         if(trim($this->it01_data) == null ){ 
           $this->erro_sql = " Campo Data da solicitação não informado.";
           $this->erro_campo = "it01_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->it01_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_hora"])){ 
       $sql  .= $virgula." it01_hora = '$this->it01_hora' ";
       $virgula = ",";
       if(trim($this->it01_hora) == null ){ 
         $this->erro_sql = " Campo Hora da solicitação não informado.";
         $this->erro_campo = "it01_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_tipotransacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_tipotransacao"])){ 
       $sql  .= $virgula." it01_tipotransacao = $this->it01_tipotransacao ";
       $virgula = ",";
       if(trim($this->it01_tipotransacao) == null ){ 
         $this->erro_sql = " Campo Tipo de Transação não informado.";
         $this->erro_campo = "it01_tipotransacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_areaterreno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_areaterreno"])){ 
       $sql  .= $virgula." it01_areaterreno = $this->it01_areaterreno ";
       $virgula = ",";
       if(trim($this->it01_areaterreno) == null ){ 
         $this->erro_sql = " Campo Àrea do terreno não informado.";
         $this->erro_campo = "it01_areaterreno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_areaedificada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_areaedificada"])){ 
       $sql  .= $virgula." it01_areaedificada = $this->it01_areaedificada ";
       $virgula = ",";
       if(trim($this->it01_areaedificada) == null ){ 
         $this->erro_sql = " Campo Área edificada não informado.";
         $this->erro_campo = "it01_areaedificada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_obs"])){ 
       $sql  .= $virgula." it01_obs = '$this->it01_obs' ";
       $virgula = ",";
     }
     if(trim($this->it01_valortransacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_valortransacao"])){ 
       $sql  .= $virgula." it01_valortransacao = $this->it01_valortransacao ";
       $virgula = ",";
       if(trim($this->it01_valortransacao) == null ){ 
         $this->erro_sql = " Campo Valor da transação não informado.";
         $this->erro_campo = "it01_valortransacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_areatrans)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_areatrans"])){ 
       $sql  .= $virgula." it01_areatrans = $this->it01_areatrans ";
       $virgula = ",";
       if(trim($this->it01_areatrans) == null ){ 
         $this->erro_sql = " Campo Área transmitida do terreno não informado.";
         $this->erro_campo = "it01_areatrans";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_mail)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_mail"])){ 
       $sql  .= $virgula." it01_mail = '$this->it01_mail' ";
       $virgula = ",";
     }
     if(trim($this->it01_finalizado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_finalizado"])){ 
       $sql  .= $virgula." it01_finalizado = '$this->it01_finalizado' ";
       $virgula = ",";
     }
     if(trim($this->it01_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_origem"])){ 
       $sql  .= $virgula." it01_origem = $this->it01_origem ";
       $virgula = ",";
       if(trim($this->it01_origem) == null ){ 
         $this->erro_sql = " Campo Origem não informado.";
         $this->erro_campo = "it01_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_id_usuario"])){ 
       $sql  .= $virgula." it01_id_usuario = $this->it01_id_usuario ";
       $virgula = ",";
       if(trim($this->it01_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "it01_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_coddepto"])){ 
       $sql  .= $virgula." it01_coddepto = $this->it01_coddepto ";
       $virgula = ",";
       if(trim($this->it01_coddepto) == null ){ 
         $this->erro_sql = " Campo Depto. não informado.";
         $this->erro_campo = "it01_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_valorterreno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_valorterreno"])){ 
        if(trim($this->it01_valorterreno)=="" && isset($GLOBALS["HTTP_POST_VARS"]["it01_valorterreno"])){ 
           $this->it01_valorterreno = "0" ; 
        } 
       $sql  .= $virgula." it01_valorterreno = $this->it01_valorterreno ";
       $virgula = ",";
     }
     if(trim($this->it01_valorconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_valorconstr"])){ 
        if(trim($this->it01_valorconstr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["it01_valorconstr"])){ 
           $this->it01_valorconstr = "0" ; 
        } 
       $sql  .= $virgula." it01_valorconstr = $this->it01_valorconstr ";
       $virgula = ",";
     }
     if(trim($this->it01_envia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_envia"])){ 
       $sql  .= $virgula." it01_envia = '$this->it01_envia' ";
       $virgula = ",";
       if(trim($this->it01_envia) == null ){ 
         $this->erro_sql = " Campo Enviado para Liberação não informado.";
         $this->erro_campo = "it01_envia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it01_percentualareatransmitida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it01_percentualareatransmitida"])){ 
       $sql  .= $virgula." it01_percentualareatransmitida = $this->it01_percentualareatransmitida ";
       $virgula = ",";
       if(trim($this->it01_percentualareatransmitida) == null ){ 
         $this->it01_percentualareatransmitida = 0;
       }
     }
     $sql .= " where ";
     if($it01_guia!=null){
       $sql .= " it01_guia = $this->it01_guia";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it01_guia));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5395,'$this->it01_guia','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_guia"]) || $this->it01_guia != "")
             $resac = db_query("insert into db_acount values($acount,792,5395,'".AddSlashes(pg_result($resaco,$conresaco,'it01_guia'))."','$this->it01_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_data"]) || $this->it01_data != "")
             $resac = db_query("insert into db_acount values($acount,792,5393,'".AddSlashes(pg_result($resaco,$conresaco,'it01_data'))."','$this->it01_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_hora"]) || $this->it01_hora != "")
             $resac = db_query("insert into db_acount values($acount,792,5394,'".AddSlashes(pg_result($resaco,$conresaco,'it01_hora'))."','$this->it01_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_tipotransacao"]) || $this->it01_tipotransacao != "")
             $resac = db_query("insert into db_acount values($acount,792,5398,'".AddSlashes(pg_result($resaco,$conresaco,'it01_tipotransacao'))."','$this->it01_tipotransacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_areaterreno"]) || $this->it01_areaterreno != "")
             $resac = db_query("insert into db_acount values($acount,792,5389,'".AddSlashes(pg_result($resaco,$conresaco,'it01_areaterreno'))."','$this->it01_areaterreno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_areaedificada"]) || $this->it01_areaedificada != "")
             $resac = db_query("insert into db_acount values($acount,792,5390,'".AddSlashes(pg_result($resaco,$conresaco,'it01_areaedificada'))."','$this->it01_areaedificada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_obs"]) || $this->it01_obs != "")
             $resac = db_query("insert into db_acount values($acount,792,5392,'".AddSlashes(pg_result($resaco,$conresaco,'it01_obs'))."','$this->it01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_valortransacao"]) || $this->it01_valortransacao != "")
             $resac = db_query("insert into db_acount values($acount,792,5402,'".AddSlashes(pg_result($resaco,$conresaco,'it01_valortransacao'))."','$this->it01_valortransacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_areatrans"]) || $this->it01_areatrans != "")
             $resac = db_query("insert into db_acount values($acount,792,5411,'".AddSlashes(pg_result($resaco,$conresaco,'it01_areatrans'))."','$this->it01_areatrans',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_mail"]) || $this->it01_mail != "")
             $resac = db_query("insert into db_acount values($acount,792,5415,'".AddSlashes(pg_result($resaco,$conresaco,'it01_mail'))."','$this->it01_mail',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_finalizado"]) || $this->it01_finalizado != "")
             $resac = db_query("insert into db_acount values($acount,792,9630,'".AddSlashes(pg_result($resaco,$conresaco,'it01_finalizado'))."','$this->it01_finalizado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_origem"]) || $this->it01_origem != "")
             $resac = db_query("insert into db_acount values($acount,792,13523,'".AddSlashes(pg_result($resaco,$conresaco,'it01_origem'))."','$this->it01_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_id_usuario"]) || $this->it01_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,792,13524,'".AddSlashes(pg_result($resaco,$conresaco,'it01_id_usuario'))."','$this->it01_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_coddepto"]) || $this->it01_coddepto != "")
             $resac = db_query("insert into db_acount values($acount,792,13525,'".AddSlashes(pg_result($resaco,$conresaco,'it01_coddepto'))."','$this->it01_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_valorterreno"]) || $this->it01_valorterreno != "")
             $resac = db_query("insert into db_acount values($acount,792,13541,'".AddSlashes(pg_result($resaco,$conresaco,'it01_valorterreno'))."','$this->it01_valorterreno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_valorconstr"]) || $this->it01_valorconstr != "")
             $resac = db_query("insert into db_acount values($acount,792,13542,'".AddSlashes(pg_result($resaco,$conresaco,'it01_valorconstr'))."','$this->it01_valorconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_envia"]) || $this->it01_envia != "")
             $resac = db_query("insert into db_acount values($acount,792,15549,'".AddSlashes(pg_result($resaco,$conresaco,'it01_envia'))."','$this->it01_envia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it01_percentualareatransmitida"]) || $this->it01_percentualareatransmitida != "")
             $resac = db_query("insert into db_acount values($acount,792,20658,'".AddSlashes(pg_result($resaco,$conresaco,'it01_percentualareatransmitida'))."','$this->it01_percentualareatransmitida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ITBI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it01_guia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ITBI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it01_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it01_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it01_guia=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($it01_guia));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,5395,'$it01_guia','E')");
           $resac  = db_query("insert into db_acount values($acount,792,5395,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5393,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5394,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5398,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_tipotransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5389,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_areaterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5390,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_areaedificada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5392,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5402,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_valortransacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5411,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_areatrans'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,5415,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_mail'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,9630,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_finalizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,13523,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,13524,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,13525,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,13541,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_valorterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,13542,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_valorconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,15549,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_envia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,792,20658,'','".AddSlashes(pg_result($resaco,$iresaco,'it01_percentualareatransmitida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from itbi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it01_guia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it01_guia = $it01_guia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ITBI nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it01_guia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ITBI nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it01_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it01_guia;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it01_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbi ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbi.it01_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = itbi.it01_coddepto";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql2 = "";
     if($dbwhere==""){
       if($it01_guia!=null ){
         $sql2 .= " where itbi.it01_guia = $it01_guia "; 
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
   function sql_query_file ( $it01_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbi ";
     $sql2 = "";
     if($dbwhere==""){
       if($it01_guia!=null ){
         $sql2 .= " where itbi.it01_guia = $it01_guia "; 
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
   function sql_query_dados( $it01_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbi ";
	 $sql .= "      inner join db_usuarios 			   on  db_usuarios.id_usuario 				 = itbi.it01_id_usuario";
     $sql .= "      inner join db_depart   			   on  db_depart.coddepto				     = itbi.it01_coddepto  ";     
     $sql .= "      inner join itbiformapagamentovalor on  itbiformapagamentovalor.it26_guia     = itbi.it01_guia									  ";
     $sql .= "      inner join itbitransacaoformapag   on  itbitransacaoformapag.it25_sequencial = itbiformapagamentovalor.it26_itbitransacaoformapag ";
     $sql .= "      inner join itbitransacao  		   on  itbitransacao.it04_codigo			 = itbitransacaoformapag.it25_itbitransacao			  ";
     $sql .= "      inner join itbiformapagamento	   on  itbiformapagamento.it27_sequencial	 = itbitransacaoformapag.it25_itbiformapagamento      ";
	 $sql .= "      left  join itbinome 			   on  itbinome.it03_guia					 = itbi.it01_guia									  ";     
     $sql .= "      left  join itbidadosimovel		   on  itbidadosimovel.it22_itbi		     = itbi.it01_guia									  ";
     $sql .= "      left  join itbidadosimovelsetorloc on  itbidadosimovelsetorloc.it29_itbidadosimovel = itbidadosimovel.it22_sequencial			  ";
     $sql .= "      left  join itbimatric			   on  itbimatric.it06_guia			    	 = itbi.it01_guia									  ";
     $sql .= "      left  join itburbano			   on  itburbano.it05_guia			    	 = itbi.it01_guia									  ";
     $sql .= "      left  join itbirural			   on  itbirural.it18_guia			    	 = itbi.it01_guia									  ";
     $sql .= "      left  join itbiruralcaract		   on  itbiruralcaract.it19_guia		   	 = itbi.it01_guia									  ";
     $sql .= "      left  join itbilocalidaderural     on  itbilocalidaderural.it33_guia   	 		        = itbi.it01_guia									  								";
     $sql .= "      left  join localidaderural         on  itbilocalidaderural.it33_localidaderural     = localidaderural.j137_sequencial 					  			";
     
     $sql2 = "";
     if($dbwhere==""){
       if($it01_guia!=null ){
         $sql2 .= " where itbi.it01_guia = $it01_guia "; 
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
   function sql_query_naolib( $it01_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbi 																	";
     $sql .= "      inner join itbinome c  	on c.it03_guia 			      = itbi.it01_guia		  ";
     $sql .= "      					             and upper(c.it03_tipo)  	  = 'C'					        "; 										 
     $sql .= "      inner join itbinome t	  on t.it03_guia 	 		      = itbi.it01_guia		  ";
     $sql .= "        					           and upper(t.it03_tipo)  	  = 'T'					        ";     
	   $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario = itbi.it01_id_usuario";
     $sql .= "      inner join db_depart    on db_depart.coddepto     = itbi.it01_coddepto  ";
     $sql .= "     	left  join itbimatric   on itbimatric.it06_guia   = itbi.it01_guia		  ";
     $sql .= "      left  join itbicancela  on itbicancela.it16_guia  = itbi.it01_guia		  ";
		 $sql .= "    	left  join itbiavalia   on itbiavalia.it14_guia   = itbi.it01_guia		  "; 
		 $sql .= "    	left  join itbirural    on itbirural.it18_guia    = itbi.it01_guia		  ";
		 $sql .= "    	left  join itburbano    on itburbano.it05_guia    = itbi.it01_guia		  ";
		 $sql .= "    	left  join iptubase     on iptubase.j01_matric    = itbimatric.it06_matric ";
		 $sql .= "    	left  join loteloc      on loteloc.j06_idbql      = iptubase.j01_idbql     ";
		 $sql .= "    	left  join setorloc     on setorloc.j05_codigo    = loteloc.j06_setorloc   ";
 
     $sql2 = "";
     if($dbwhere==""){
       if($it01_guia!=null ){
         $sql2 .= " where itbi.it01_guia = $it01_guia "; 
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
   function sql_query_pag( $it01_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbi ";
     $sql .= "      inner join itbiformapagamentovalor on  itbiformapagamentovalor.it26_guia     = itbi.it01_guia									  ";
     $sql .= "      inner join itbitransacaoformapag   on  itbitransacaoformapag.it25_sequencial = itbiformapagamentovalor.it26_itbitransacaoformapag ";
     $sql .= "      inner join itbitransacao  		   on  itbitransacao.it04_codigo			 = itbitransacaoformapag.it25_itbitransacao			  ";
     $sql .= "      inner join itbiformapagamento	   on  itbiformapagamento.it27_sequencial	 = itbitransacaoformapag.it25_itbiformapagamento      ";
	 $sql .= "      inner join itbitipoformapag   	   on  itbitipoformapag.it28_sequencial      = itbiformapagamento.it27_itbitipoformapag";          
     $sql .= "      left  join itbiavalia			   on  itbiavalia.it14_guia			   		 = itbi.it01_guia									  ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($it01_guia!=null ){
         $sql2 .= " where itbi.it01_guia = $it01_guia "; 
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
   function sql_query_lib ( $it01_guia=null,$campos="*",$ordem=null,$dbwhere=""){
     
$sql = "select ";
if($campos != "*" ){
$campos_sql = split("#",$campos);
$virgula = "";
$totallinha = count($campos_sql);
for($i=0;$i<$totallinha;$i++){
$sql .= $virgula.$campos_sql[$i];
$virgula = ",";
}
}else{
$sql .= $campos;
}
$sql .= " from itbi ";
$sql .= " inner join itbitransacao on itbitransacao.it04_codigo = itbi.it01_tipotransacao";
$sql .= " inner join itbiavalia on itbiavalia.it14_guia = itbi.it01_guia";
$sql2 = "";
if($dbwhere==""){
if($it01_guia!=null ){
$sql2 .= " where itbi.it01_guia = $it01_guia ";
}
}else if($dbwhere != ""){
$sql2 = " where $dbwhere";
}
$sql .= $sql2;
if($ordem != null ){
$sql .= " order by ";
$campos_sql = split("#",$ordem);
$virgula = "";
$totallinha = count($campos_sql);
for($i=0;$i<$totallinha;$i++){
$sql .= $virgula.$campos_sql[$i];
$virgula = ",";
}
}
return $sql;
}
   function sql_query_itbi( $it01_guia=null,$campos="*",$ordem=null,$dbwhere="",$sWhereLogradouro=""){ 
     $sSql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sSql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sSql .= $campos;
     }
    $sSql .= " from itbi                                                                                                  ";
    $sSql .= "                    inner join db_usuarios       on db_usuarios.id_usuario     = itbi.it01_id_usuario       ";
    $sSql .= "                    inner join db_depart         on db_depart.coddepto         = itbi.it01_coddepto         ";
		$sSql .= "                    inner join itbitransacao     on it04_codigo                = it01_tipotransacao         ";
		$sSql .= "                    left  join itbinome          on itbinome.it03_guia         = itbi.it01_guia             ";
		$sSql .= "                    left  join itburbano         on itburbano.it05_guia        = itbi.it01_guia             ";
		$sSql .= "                    left  join itbirural         on itbirural.it18_guia        = itbi.it01_guia             ";
		$sSql .= "                    left  join itbicancela       on itbicancela.it16_guia      = itbi.it01_guia             ";
		$sSql .= "                    left  join itbiavalia        on itbiavalia.it14_guia       = itbi.it01_guia             ";
		$sSql .= "                    left  join itbinomecgm       on itbinomecgm.it21_itbinome  = itbi.it01_guia             ";
		$sSql .= "                    left  join itbicgm           on itbicgm.it02_guia          = itbi.it01_guia             ";
		$sSql .= "                    left  join itbidadosimovel   on itbidadosimovel.it22_itbi  = itbi.it01_guia             ";
		$sSql .= "                    left  join itbimatric        on itbi.it01_guia             = itbimatric.it06_guia       ";
		$sSql .= "                    left  join iptubase          on it06_matric                = j01_matric                 ";
		$sSql .= "                    left  join lote              on j34_idbql                  = j01_idbql                  ";
		$sSql .= "                    left  join cgm               on z01_numcgm                 = it21_numcgm                ";
		$sSql .= "                    left  join itbinumpre        on itbinumpre.it15_guia       = itbi.it01_guia             ";
		$sSql .= "                    left  join recibo            on recibo.k00_numpre          = it15_numpre                ";
		$sSql .= "                    left  join arrepaga          on arrepaga.k00_numpre        = itbinumpre.it15_numpre     ";
		$sSql .= "                    left  join loteloc           on j06_idbql                  = j01_idbql                  ";
		$sSql .= "                    left  join setorloc          on j05_codigo                 = j06_setorloc               ";

 
     $sql2 = "";
     if($dbwhere==""){
       if($it01_guia!=null ){
         $sql2 .= " where itbi.it01_guia = $it01_guia "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sSql .= $sql2;
     if($ordem != null ){
       $sSql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sSql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     
     
     
     $sSql = "select * from ( {$sSql} ) as x {$sWhereLogradouro}";
     
     return $sSql;
  }
   function sql_query_canc( $it01_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbi                                                                                              ";
     $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario = itbi.it01_id_usuario                          ";
     $sql .= "      inner join db_depart    on db_depart.coddepto     = itbi.it01_coddepto                            ";
     $sql .= "      left  join itbimatric   on itbimatric.it06_guia   = itbi.it01_guia                                ";
     $sql .= "      left  join itbiavalia   on itbiavalia.it14_guia   = itbi.it01_guia                                "; 
     $sql .= "      left  join itbinumpre   on itbinumpre.it15_guia   = itbi.it01_guia                                ";
     $sql .= "      left  join itbinome     on itbinome.it03_guia     = itbi.it01_guia                                "; 
     $sql .= "      left  join itbicancela  on itbicancela.it16_guia  = itbi.it01_guia                                ";
     $sql .= "      left  join itbirural    on itbirural.it18_guia    = itbi.it01_guia                                ";
     $sql .= "      left  join itburbano    on itburbano.it05_guia    = itbi.it01_guia                                ";

 
     $sql2 = "";
     if($dbwhere==""){
       if($it01_guia!=null ){
         $sql2 .= " where itbi.it01_guia = $it01_guia "; 
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