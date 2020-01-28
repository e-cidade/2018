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

//MODULO: issqn
//CLASSE DA ENTIDADE issplanit
class cl_issplanit { 
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
   var $q21_sequencial = 0; 
   var $q21_planilha = 0; 
   var $q21_cnpj = null; 
   var $q21_nome = null; 
   var $q21_servico = null; 
   var $q21_nota = null; 
   var $q21_serie = null; 
   var $q21_valorser = 0; 
   var $q21_aliq = 0; 
   var $q21_valor = 0; 
   var $q21_dataop_dia = null; 
   var $q21_dataop_mes = null; 
   var $q21_dataop_ano = null; 
   var $q21_dataop = null; 
   var $q21_horaop = null; 
   var $q21_tipolanc = 0; 
   var $q21_situacao = 0; 
   var $q21_valordeducao = 0; 
   var $q21_valorbase = 0; 
   var $q21_retido = 'f'; 
   var $q21_obs = null; 
   var $q21_datanota_dia = null; 
   var $q21_datanota_mes = null; 
   var $q21_datanota_ano = null; 
   var $q21_datanota = null; 
   var $q21_valorimposto = 0; 
   var $q21_status = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q21_sequencial = int4 = q21_sequencial 
                 q21_planilha = int4 = Código da Planilha 
                 q21_cnpj = varchar(40) = CNPJ 
                 q21_nome = varchar(60) = Nome 
                 q21_servico = varchar(40) = Serviço 
                 q21_nota = varchar(20) = Nota 
                 q21_serie = varchar(5) = Série 
                 q21_valorser = float8 = Valor do Serviço 
                 q21_aliq = float8 = Alíquota 
                 q21_valor = float8 = Valor 
                 q21_dataop = date = Data da operação 
                 q21_horaop = char(5) = hora da operação 
                 q21_tipolanc = int4 = Tipo de serviço 
                 q21_situacao = int4 = Situação 
                 q21_valordeducao = float8 = Dedução 
                 q21_valorbase = float8 = Base de cálculo 
                 q21_retido = bool = Imposto retido 
                 q21_obs = text = Observação 
                 q21_datanota = date = Data da nota 
                 q21_valorimposto = float8 = Valor do Imposto 
                 q21_status = int4 = Status 
                 ";
   //funcao construtor da classe 
   function cl_issplanit() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issplanit"); 
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
       $this->q21_sequencial = ($this->q21_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_sequencial"]:$this->q21_sequencial);
       $this->q21_planilha = ($this->q21_planilha == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_planilha"]:$this->q21_planilha);
       $this->q21_cnpj = ($this->q21_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_cnpj"]:$this->q21_cnpj);
       $this->q21_nome = ($this->q21_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_nome"]:$this->q21_nome);
       $this->q21_servico = ($this->q21_servico == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_servico"]:$this->q21_servico);
       $this->q21_nota = ($this->q21_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_nota"]:$this->q21_nota);
       $this->q21_serie = ($this->q21_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_serie"]:$this->q21_serie);
       $this->q21_valorser = ($this->q21_valorser == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_valorser"]:$this->q21_valorser);
       $this->q21_aliq = ($this->q21_aliq == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_aliq"]:$this->q21_aliq);
       $this->q21_valor = ($this->q21_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_valor"]:$this->q21_valor);
       if($this->q21_dataop == ""){
         $this->q21_dataop_dia = ($this->q21_dataop_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_dataop_dia"]:$this->q21_dataop_dia);
         $this->q21_dataop_mes = ($this->q21_dataop_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_dataop_mes"]:$this->q21_dataop_mes);
         $this->q21_dataop_ano = ($this->q21_dataop_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_dataop_ano"]:$this->q21_dataop_ano);
         if($this->q21_dataop_dia != ""){
            $this->q21_dataop = $this->q21_dataop_ano."-".$this->q21_dataop_mes."-".$this->q21_dataop_dia;
         }
       }
       $this->q21_horaop = ($this->q21_horaop == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_horaop"]:$this->q21_horaop);
       $this->q21_tipolanc = ($this->q21_tipolanc == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_tipolanc"]:$this->q21_tipolanc);
       $this->q21_situacao = ($this->q21_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_situacao"]:$this->q21_situacao);
       $this->q21_valordeducao = ($this->q21_valordeducao == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_valordeducao"]:$this->q21_valordeducao);
       $this->q21_valorbase = ($this->q21_valorbase == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_valorbase"]:$this->q21_valorbase);
       $this->q21_retido = ($this->q21_retido == "f"?@$GLOBALS["HTTP_POST_VARS"]["q21_retido"]:$this->q21_retido);
       $this->q21_obs = ($this->q21_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_obs"]:$this->q21_obs);
       if($this->q21_datanota == ""){
         $this->q21_datanota_dia = ($this->q21_datanota_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_datanota_dia"]:$this->q21_datanota_dia);
         $this->q21_datanota_mes = ($this->q21_datanota_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_datanota_mes"]:$this->q21_datanota_mes);
         $this->q21_datanota_ano = ($this->q21_datanota_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_datanota_ano"]:$this->q21_datanota_ano);
         if($this->q21_datanota_dia != ""){
            $this->q21_datanota = $this->q21_datanota_ano."-".$this->q21_datanota_mes."-".$this->q21_datanota_dia;
         }
       }
       $this->q21_valorimposto = ($this->q21_valorimposto == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_valorimposto"]:$this->q21_valorimposto);
       $this->q21_status = ($this->q21_status == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_status"]:$this->q21_status);
     }else{
       $this->q21_sequencial = ($this->q21_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q21_sequencial"]:$this->q21_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q21_sequencial){ 
      $this->atualizacampos();
     if($this->q21_planilha == null ){ 
       $this->erro_sql = " Campo Código da Planilha nao Informado.";
       $this->erro_campo = "q21_planilha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_servico == null ){ 
       $this->erro_sql = " Campo Serviço nao Informado.";
       $this->erro_campo = "q21_servico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_nota == null ){ 
       $this->erro_sql = " Campo Nota nao Informado.";
       $this->erro_campo = "q21_nota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_valorser == null ){ 
       $this->erro_sql = " Campo Valor do Serviço nao Informado.";
       $this->erro_campo = "q21_valorser";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_aliq == null ){ 
       $this->erro_sql = " Campo Alíquota nao Informado.";
       $this->erro_campo = "q21_aliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "q21_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_dataop == null ){ 
       $this->q21_dataop = "null";
     }
     if($this->q21_tipolanc == null ){ 
       $this->erro_sql = " Campo Tipo de serviço nao Informado.";
       $this->erro_campo = "q21_tipolanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "q21_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_valordeducao == null ){ 
       $this->erro_sql = " Campo Dedução nao Informado.";
       $this->erro_campo = "q21_valordeducao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_valorbase == null ){ 
       $this->erro_sql = " Campo Base de cálculo nao Informado.";
       $this->erro_campo = "q21_valorbase";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_retido == null ){ 
       $this->erro_sql = " Campo Imposto retido nao Informado.";
       $this->erro_campo = "q21_retido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_datanota == null ){ 
       $this->q21_datanota = "null";
     }
     if($this->q21_valorimposto == null ){ 
       $this->erro_sql = " Campo Valor do Imposto nao Informado.";
       $this->erro_campo = "q21_valorimposto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q21_status == null ){ 
       $this->erro_sql = " Campo Status nao Informado.";
       $this->erro_campo = "q21_status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q21_sequencial == "" || $q21_sequencial == null ){
       $result = db_query("select nextval('issplanit_q21_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issplanit_q21_sequencial_seq do campo: q21_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q21_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from issplanit_q21_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q21_sequencial)){
         $this->erro_sql = " Campo q21_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q21_sequencial = $q21_sequencial; 
       }
     }
     if(($this->q21_sequencial == null) || ($this->q21_sequencial == "") ){ 
       $this->erro_sql = " Campo q21_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issplanit(
                                       q21_sequencial 
                                      ,q21_planilha 
                                      ,q21_cnpj 
                                      ,q21_nome 
                                      ,q21_servico 
                                      ,q21_nota 
                                      ,q21_serie 
                                      ,q21_valorser 
                                      ,q21_aliq 
                                      ,q21_valor 
                                      ,q21_dataop 
                                      ,q21_horaop 
                                      ,q21_tipolanc 
                                      ,q21_situacao 
                                      ,q21_valordeducao 
                                      ,q21_valorbase 
                                      ,q21_retido 
                                      ,q21_obs 
                                      ,q21_datanota 
                                      ,q21_valorimposto 
                                      ,q21_status 
                       )
                values (
                                $this->q21_sequencial 
                               ,$this->q21_planilha 
                               ,'$this->q21_cnpj' 
                               ,'$this->q21_nome' 
                               ,'$this->q21_servico' 
                               ,'$this->q21_nota' 
                               ,'$this->q21_serie' 
                               ,$this->q21_valorser 
                               ,$this->q21_aliq 
                               ,$this->q21_valor 
                               ,".($this->q21_dataop == "null" || $this->q21_dataop == ""?"null":"'".$this->q21_dataop."'")." 
                               ,'$this->q21_horaop' 
                               ,$this->q21_tipolanc 
                               ,$this->q21_situacao 
                               ,$this->q21_valordeducao 
                               ,$this->q21_valorbase 
                               ,'$this->q21_retido' 
                               ,'$this->q21_obs' 
                               ,".($this->q21_datanota == "null" || $this->q21_datanota == ""?"null":"'".$this->q21_datanota."'")." 
                               ,$this->q21_valorimposto 
                               ,$this->q21_status 
                      )";
     
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "itens da planilha de retenção na fonte ($this->q21_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "itens da planilha de retenção na fonte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "itens da planilha de retenção na fonte ($this->q21_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q21_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q21_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9202,'$this->q21_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,422,9202,'','".AddSlashes(pg_result($resaco,0,'q21_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2595,'','".AddSlashes(pg_result($resaco,0,'q21_planilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2596,'','".AddSlashes(pg_result($resaco,0,'q21_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2598,'','".AddSlashes(pg_result($resaco,0,'q21_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2599,'','".AddSlashes(pg_result($resaco,0,'q21_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2600,'','".AddSlashes(pg_result($resaco,0,'q21_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2601,'','".AddSlashes(pg_result($resaco,0,'q21_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2602,'','".AddSlashes(pg_result($resaco,0,'q21_valorser'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2603,'','".AddSlashes(pg_result($resaco,0,'q21_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,2604,'','".AddSlashes(pg_result($resaco,0,'q21_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,9209,'','".AddSlashes(pg_result($resaco,0,'q21_dataop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,9211,'','".AddSlashes(pg_result($resaco,0,'q21_horaop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,10538,'','".AddSlashes(pg_result($resaco,0,'q21_tipolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,10539,'','".AddSlashes(pg_result($resaco,0,'q21_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,10540,'','".AddSlashes(pg_result($resaco,0,'q21_valordeducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,10541,'','".AddSlashes(pg_result($resaco,0,'q21_valorbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,10542,'','".AddSlashes(pg_result($resaco,0,'q21_retido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,10543,'','".AddSlashes(pg_result($resaco,0,'q21_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,10544,'','".AddSlashes(pg_result($resaco,0,'q21_datanota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,10545,'','".AddSlashes(pg_result($resaco,0,'q21_valorimposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,422,12000,'','".AddSlashes(pg_result($resaco,0,'q21_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q21_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issplanit set ";
     $virgula = "";
     if(trim($this->q21_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_sequencial"])){ 
       $sql  .= $virgula." q21_sequencial = $this->q21_sequencial ";
       $virgula = ",";
       if(trim($this->q21_sequencial) == null ){ 
         $this->erro_sql = " Campo q21_sequencial nao Informado.";
         $this->erro_campo = "q21_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_planilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_planilha"])){ 
       $sql  .= $virgula." q21_planilha = $this->q21_planilha ";
       $virgula = ",";
       if(trim($this->q21_planilha) == null ){ 
         $this->erro_sql = " Campo Código da Planilha nao Informado.";
         $this->erro_campo = "q21_planilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_cnpj"])){ 
       $sql  .= $virgula." q21_cnpj = '$this->q21_cnpj' ";
       $virgula = ",";
     }
     if(trim($this->q21_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_nome"])){ 
       $sql  .= $virgula." q21_nome = '$this->q21_nome' ";
       $virgula = ",";
     }
     if(trim($this->q21_servico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_servico"])){ 
       $sql  .= $virgula." q21_servico = '$this->q21_servico' ";
       $virgula = ",";
       if(trim($this->q21_servico) == null ){ 
         $this->erro_sql = " Campo Serviço nao Informado.";
         $this->erro_campo = "q21_servico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_nota"])){ 
       $sql  .= $virgula." q21_nota = '$this->q21_nota' ";
       $virgula = ",";
       if(trim($this->q21_nota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "q21_nota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_serie"])){ 
       $sql  .= $virgula." q21_serie = '$this->q21_serie' ";
       $virgula = ",";
     }
     if(trim($this->q21_valorser)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_valorser"])){ 
       $sql  .= $virgula." q21_valorser = $this->q21_valorser ";
       $virgula = ",";
       if(trim($this->q21_valorser) == null ){ 
         $this->erro_sql = " Campo Valor do Serviço nao Informado.";
         $this->erro_campo = "q21_valorser";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_aliq"])){ 
       $sql  .= $virgula." q21_aliq = $this->q21_aliq ";
       $virgula = ",";
       if(trim($this->q21_aliq) == null ){ 
         $this->erro_sql = " Campo Alíquota nao Informado.";
         $this->erro_campo = "q21_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_valor"])){ 
       $sql  .= $virgula." q21_valor = $this->q21_valor ";
       $virgula = ",";
       if(trim($this->q21_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "q21_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_dataop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_dataop_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q21_dataop_dia"] !="") ){ 
       $sql  .= $virgula." q21_dataop = '$this->q21_dataop' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q21_dataop_dia"])){ 
         $sql  .= $virgula." q21_dataop = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q21_horaop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_horaop"])){ 
       $sql  .= $virgula." q21_horaop = '$this->q21_horaop' ";
       $virgula = ",";
     }
     if(trim($this->q21_tipolanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_tipolanc"])){ 
       $sql  .= $virgula." q21_tipolanc = $this->q21_tipolanc ";
       $virgula = ",";
       if(trim($this->q21_tipolanc) == null ){ 
         $this->erro_sql = " Campo Tipo de serviço nao Informado.";
         $this->erro_campo = "q21_tipolanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_situacao"])){ 
       $sql  .= $virgula." q21_situacao = $this->q21_situacao ";
       $virgula = ",";
       if(trim($this->q21_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "q21_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_valordeducao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_valordeducao"])){ 
       $sql  .= $virgula." q21_valordeducao = $this->q21_valordeducao ";
       $virgula = ",";
       if(trim($this->q21_valordeducao) == null ){ 
         $this->erro_sql = " Campo Dedução nao Informado.";
         $this->erro_campo = "q21_valordeducao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_valorbase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_valorbase"])){ 
       $sql  .= $virgula." q21_valorbase = $this->q21_valorbase ";
       $virgula = ",";
       if(trim($this->q21_valorbase) == null ){ 
         $this->erro_sql = " Campo Base de cálculo nao Informado.";
         $this->erro_campo = "q21_valorbase";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_retido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_retido"])){ 
       $sql  .= $virgula." q21_retido = '$this->q21_retido' ";
       $virgula = ",";
       if(trim($this->q21_retido) == null ){ 
         $this->erro_sql = " Campo Imposto retido nao Informado.";
         $this->erro_campo = "q21_retido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_obs"])){ 
       $sql  .= $virgula." q21_obs = '$this->q21_obs' ";
       $virgula = ",";
     }
     if(trim($this->q21_datanota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_datanota_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q21_datanota_dia"] !="") ){ 
       $sql  .= $virgula." q21_datanota = '$this->q21_datanota' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q21_datanota_dia"])){ 
         $sql  .= $virgula." q21_datanota = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q21_valorimposto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_valorimposto"])){ 
       $sql  .= $virgula." q21_valorimposto = $this->q21_valorimposto ";
       $virgula = ",";
       if(trim($this->q21_valorimposto) == null ){ 
         $this->erro_sql = " Campo Valor do Imposto nao Informado.";
         $this->erro_campo = "q21_valorimposto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q21_status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q21_status"])){ 
       $sql  .= $virgula." q21_status = $this->q21_status ";
       $virgula = ",";
       if(trim($this->q21_status) == null ){ 
         $this->erro_sql = " Campo Status nao Informado.";
         $this->erro_campo = "q21_status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q21_sequencial!=null){
       $sql .= " q21_sequencial = $this->q21_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q21_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,9202,'$this->q21_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_sequencial"]) || $this->q21_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,422,9202,'".AddSlashes(pg_result($resaco,$conresaco,'q21_sequencial'))."','$this->q21_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_planilha"]) || $this->q21_planilha != "")
             $resac = db_query("insert into db_acount values($acount,422,2595,'".AddSlashes(pg_result($resaco,$conresaco,'q21_planilha'))."','$this->q21_planilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_cnpj"]) || $this->q21_cnpj != "")
             $resac = db_query("insert into db_acount values($acount,422,2596,'".AddSlashes(pg_result($resaco,$conresaco,'q21_cnpj'))."','$this->q21_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_nome"]) || $this->q21_nome != "")
             $resac = db_query("insert into db_acount values($acount,422,2598,'".AddSlashes(pg_result($resaco,$conresaco,'q21_nome'))."','$this->q21_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_servico"]) || $this->q21_servico != "")
             $resac = db_query("insert into db_acount values($acount,422,2599,'".AddSlashes(pg_result($resaco,$conresaco,'q21_servico'))."','$this->q21_servico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_nota"]) || $this->q21_nota != "")
             $resac = db_query("insert into db_acount values($acount,422,2600,'".AddSlashes(pg_result($resaco,$conresaco,'q21_nota'))."','$this->q21_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_serie"]) || $this->q21_serie != "")
             $resac = db_query("insert into db_acount values($acount,422,2601,'".AddSlashes(pg_result($resaco,$conresaco,'q21_serie'))."','$this->q21_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_valorser"]) || $this->q21_valorser != "")
             $resac = db_query("insert into db_acount values($acount,422,2602,'".AddSlashes(pg_result($resaco,$conresaco,'q21_valorser'))."','$this->q21_valorser',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_aliq"]) || $this->q21_aliq != "")
             $resac = db_query("insert into db_acount values($acount,422,2603,'".AddSlashes(pg_result($resaco,$conresaco,'q21_aliq'))."','$this->q21_aliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_valor"]) || $this->q21_valor != "")
             $resac = db_query("insert into db_acount values($acount,422,2604,'".AddSlashes(pg_result($resaco,$conresaco,'q21_valor'))."','$this->q21_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_dataop"]) || $this->q21_dataop != "")
             $resac = db_query("insert into db_acount values($acount,422,9209,'".AddSlashes(pg_result($resaco,$conresaco,'q21_dataop'))."','$this->q21_dataop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_horaop"]) || $this->q21_horaop != "")
             $resac = db_query("insert into db_acount values($acount,422,9211,'".AddSlashes(pg_result($resaco,$conresaco,'q21_horaop'))."','$this->q21_horaop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_tipolanc"]) || $this->q21_tipolanc != "")
             $resac = db_query("insert into db_acount values($acount,422,10538,'".AddSlashes(pg_result($resaco,$conresaco,'q21_tipolanc'))."','$this->q21_tipolanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_situacao"]) || $this->q21_situacao != "")
             $resac = db_query("insert into db_acount values($acount,422,10539,'".AddSlashes(pg_result($resaco,$conresaco,'q21_situacao'))."','$this->q21_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_valordeducao"]) || $this->q21_valordeducao != "")
             $resac = db_query("insert into db_acount values($acount,422,10540,'".AddSlashes(pg_result($resaco,$conresaco,'q21_valordeducao'))."','$this->q21_valordeducao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_valorbase"]) || $this->q21_valorbase != "")
             $resac = db_query("insert into db_acount values($acount,422,10541,'".AddSlashes(pg_result($resaco,$conresaco,'q21_valorbase'))."','$this->q21_valorbase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_retido"]) || $this->q21_retido != "")
             $resac = db_query("insert into db_acount values($acount,422,10542,'".AddSlashes(pg_result($resaco,$conresaco,'q21_retido'))."','$this->q21_retido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_obs"]) || $this->q21_obs != "")
             $resac = db_query("insert into db_acount values($acount,422,10543,'".AddSlashes(pg_result($resaco,$conresaco,'q21_obs'))."','$this->q21_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_datanota"]) || $this->q21_datanota != "")
             $resac = db_query("insert into db_acount values($acount,422,10544,'".AddSlashes(pg_result($resaco,$conresaco,'q21_datanota'))."','$this->q21_datanota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_valorimposto"]) || $this->q21_valorimposto != "")
             $resac = db_query("insert into db_acount values($acount,422,10545,'".AddSlashes(pg_result($resaco,$conresaco,'q21_valorimposto'))."','$this->q21_valorimposto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q21_status"]) || $this->q21_status != "")
             $resac = db_query("insert into db_acount values($acount,422,12000,'".AddSlashes(pg_result($resaco,$conresaco,'q21_status'))."','$this->q21_status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itens da planilha de retenção na fonte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q21_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itens da planilha de retenção na fonte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q21_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($q21_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,9202,'$q21_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,422,9202,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2595,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_planilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2596,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2598,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2599,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2600,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2601,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2602,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_valorser'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2603,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,2604,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,9209,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_dataop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,9211,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_horaop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,10538,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_tipolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,10539,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,10540,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_valordeducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,10541,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_valorbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,10542,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_retido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,10543,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,10544,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_datanota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,10545,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_valorimposto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,422,12000,'','".AddSlashes(pg_result($resaco,$iresaco,'q21_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from issplanit
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q21_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q21_sequencial = $q21_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itens da planilha de retenção na fonte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q21_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itens da planilha de retenção na fonte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q21_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issplanit";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplanit ";
     $sql .= "      inner join issplan  on  issplan.q20_planilha = issplanit.q21_planilha";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issplan.q20_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q21_sequencial!=null ){
         $sql2 .= " where issplanit.q21_sequencial = $q21_sequencial "; 
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
   function sql_query_file ( $q21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplanit ";
     $sql2 = "";
     if($dbwhere==""){
       if($q21_sequencial!=null ){
         $sql2 .= " where issplanit.q21_sequencial = $q21_sequencial "; 
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