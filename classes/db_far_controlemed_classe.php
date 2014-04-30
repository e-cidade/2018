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

//MODULO: Farmácia
//CLASSE DA ENTIDADE far_controlemed
class cl_far_controlemed { 
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
   var $fa10_i_codigo = 0; 
   var $fa10_i_medicamento = 0; 
   var $fa10_i_quantidade = 0; 
   var $fa10_i_controle = 0; 
   var $fa10_i_prazo = 0; 
   var $fa10_d_dataini_dia = null; 
   var $fa10_d_dataini_mes = null; 
   var $fa10_d_dataini_ano = null; 
   var $fa10_d_dataini = null; 
   var $fa10_d_datafim_dia = null; 
   var $fa10_d_datafim_mes = null; 
   var $fa10_d_datafim_ano = null; 
   var $fa10_d_datafim = null; 
   var $fa10_i_margem = 0; 
   var $fa10_i_programa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa10_i_codigo = int4 = Código 
                 fa10_i_medicamento = int4 = Medicamento 
                 fa10_i_quantidade = int4 = Quantidade 
                 fa10_i_controle = int4 = Controle 
                 fa10_i_prazo = int4 = Prazo 
                 fa10_d_dataini = date = Início 
                 fa10_d_datafim = date = Fim 
                 fa10_i_margem = int4 = Margem 
                 fa10_i_programa = int4 = Programa 
                 ";
   //funcao construtor da classe 
   function cl_far_controlemed() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_controlemed"); 
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
       $this->fa10_i_codigo = ($this->fa10_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_i_codigo"]:$this->fa10_i_codigo);
       $this->fa10_i_medicamento = ($this->fa10_i_medicamento == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_i_medicamento"]:$this->fa10_i_medicamento);
       $this->fa10_i_quantidade = ($this->fa10_i_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_i_quantidade"]:$this->fa10_i_quantidade);
       $this->fa10_i_controle = ($this->fa10_i_controle == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_i_controle"]:$this->fa10_i_controle);
       $this->fa10_i_prazo = ($this->fa10_i_prazo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_i_prazo"]:$this->fa10_i_prazo);
       if($this->fa10_d_dataini == ""){
         $this->fa10_d_dataini_dia = ($this->fa10_d_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_d_dataini_dia"]:$this->fa10_d_dataini_dia);
         $this->fa10_d_dataini_mes = ($this->fa10_d_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_d_dataini_mes"]:$this->fa10_d_dataini_mes);
         $this->fa10_d_dataini_ano = ($this->fa10_d_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_d_dataini_ano"]:$this->fa10_d_dataini_ano);
         if($this->fa10_d_dataini_dia != ""){
            $this->fa10_d_dataini = $this->fa10_d_dataini_ano."-".$this->fa10_d_dataini_mes."-".$this->fa10_d_dataini_dia;
         }
       }
       if($this->fa10_d_datafim == ""){
         $this->fa10_d_datafim_dia = ($this->fa10_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_d_datafim_dia"]:$this->fa10_d_datafim_dia);
         $this->fa10_d_datafim_mes = ($this->fa10_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_d_datafim_mes"]:$this->fa10_d_datafim_mes);
         $this->fa10_d_datafim_ano = ($this->fa10_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_d_datafim_ano"]:$this->fa10_d_datafim_ano);
         if($this->fa10_d_datafim_dia != ""){
            $this->fa10_d_datafim = $this->fa10_d_datafim_ano."-".$this->fa10_d_datafim_mes."-".$this->fa10_d_datafim_dia;
         }
       }
       $this->fa10_i_margem = ($this->fa10_i_margem == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_i_margem"]:$this->fa10_i_margem);
       $this->fa10_i_programa = ($this->fa10_i_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_i_programa"]:$this->fa10_i_programa);
     }else{
       $this->fa10_i_codigo = ($this->fa10_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa10_i_codigo"]:$this->fa10_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa10_i_codigo){ 
      $this->atualizacampos();
     if($this->fa10_i_medicamento == null ){ 
       $this->erro_sql = " Campo Medicamento nao Informado.";
       $this->erro_campo = "fa10_i_medicamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa10_i_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "fa10_i_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa10_i_controle == null ){ 
       $this->erro_sql = " Campo Controle nao Informado.";
       $this->erro_campo = "fa10_i_controle";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa10_i_prazo == null ){ 
       $this->erro_sql = " Campo Prazo nao Informado.";
       $this->erro_campo = "fa10_i_prazo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa10_d_dataini == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "fa10_d_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa10_d_datafim == null ){ 
       $this->fa10_d_datafim = "null";
     }
     if($this->fa10_i_margem == null ){ 
       $this->erro_sql = " Campo Margem nao Informado.";
       $this->erro_campo = "fa10_i_margem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa10_i_programa == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "fa10_i_programa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa10_i_codigo == "" || $fa10_i_codigo == null ){
       $result = db_query("select nextval('far_controlemed_fa10_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_controlemed_fa10_codigo_seq do campo: fa10_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa10_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_controlemed_fa10_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa10_i_codigo)){
         $this->erro_sql = " Campo fa10_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa10_i_codigo = $fa10_i_codigo; 
       }
     }
     if(($this->fa10_i_codigo == null) || ($this->fa10_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa10_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_controlemed(
                                       fa10_i_codigo 
                                      ,fa10_i_medicamento 
                                      ,fa10_i_quantidade 
                                      ,fa10_i_controle 
                                      ,fa10_i_prazo 
                                      ,fa10_d_dataini 
                                      ,fa10_d_datafim 
                                      ,fa10_i_margem 
                                      ,fa10_i_programa 
                       )
                values (
                                $this->fa10_i_codigo 
                               ,$this->fa10_i_medicamento 
                               ,$this->fa10_i_quantidade 
                               ,$this->fa10_i_controle 
                               ,$this->fa10_i_prazo 
                               ,".($this->fa10_d_dataini == "null" || $this->fa10_d_dataini == ""?"null":"'".$this->fa10_d_dataini."'")." 
                               ,".($this->fa10_d_datafim == "null" || $this->fa10_d_datafim == ""?"null":"'".$this->fa10_d_datafim."'")." 
                               ,$this->fa10_i_margem 
                               ,$this->fa10_i_programa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_controlemed ($this->fa10_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_controlemed já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_controlemed ($this->fa10_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa10_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa10_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12488,'$this->fa10_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2179,12488,'','".AddSlashes(pg_result($resaco,0,'fa10_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2179,12489,'','".AddSlashes(pg_result($resaco,0,'fa10_i_medicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2179,12490,'','".AddSlashes(pg_result($resaco,0,'fa10_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2179,12491,'','".AddSlashes(pg_result($resaco,0,'fa10_i_controle'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2179,12492,'','".AddSlashes(pg_result($resaco,0,'fa10_i_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2179,12493,'','".AddSlashes(pg_result($resaco,0,'fa10_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2179,12494,'','".AddSlashes(pg_result($resaco,0,'fa10_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2179,12495,'','".AddSlashes(pg_result($resaco,0,'fa10_i_margem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2179,12496,'','".AddSlashes(pg_result($resaco,0,'fa10_i_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa10_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_controlemed set ";
     $virgula = "";
     if(trim($this->fa10_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_codigo"])){ 
       $sql  .= $virgula." fa10_i_codigo = $this->fa10_i_codigo ";
       $virgula = ",";
       if(trim($this->fa10_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa10_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa10_i_medicamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_medicamento"])){ 
       $sql  .= $virgula." fa10_i_medicamento = $this->fa10_i_medicamento ";
       $virgula = ",";
       if(trim($this->fa10_i_medicamento) == null ){ 
         $this->erro_sql = " Campo Medicamento nao Informado.";
         $this->erro_campo = "fa10_i_medicamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa10_i_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_quantidade"])){ 
       $sql  .= $virgula." fa10_i_quantidade = $this->fa10_i_quantidade ";
       $virgula = ",";
       if(trim($this->fa10_i_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "fa10_i_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa10_i_controle)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_controle"])){ 
       $sql  .= $virgula." fa10_i_controle = $this->fa10_i_controle ";
       $virgula = ",";
       if(trim($this->fa10_i_controle) == null ){ 
         $this->erro_sql = " Campo Controle nao Informado.";
         $this->erro_campo = "fa10_i_controle";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa10_i_prazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_prazo"])){ 
       $sql  .= $virgula." fa10_i_prazo = $this->fa10_i_prazo ";
       $virgula = ",";
       if(trim($this->fa10_i_prazo) == null ){ 
         $this->erro_sql = " Campo Prazo nao Informado.";
         $this->erro_campo = "fa10_i_prazo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa10_d_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_d_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa10_d_dataini_dia"] !="") ){ 
       $sql  .= $virgula." fa10_d_dataini = '$this->fa10_d_dataini' ";
       $virgula = ",";
       if(trim($this->fa10_d_dataini) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "fa10_d_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_d_dataini_dia"])){ 
         $sql  .= $virgula." fa10_d_dataini = null ";
         $virgula = ",";
         if(trim($this->fa10_d_dataini) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "fa10_d_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa10_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa10_d_datafim_dia"] !="") ){ 
       $sql  .= $virgula." fa10_d_datafim = '$this->fa10_d_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_d_datafim_dia"])){ 
         $sql  .= $virgula." fa10_d_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->fa10_i_margem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_margem"])){ 
       $sql  .= $virgula." fa10_i_margem = $this->fa10_i_margem ";
       $virgula = ",";
       if(trim($this->fa10_i_margem) == null ){ 
         $this->erro_sql = " Campo Margem nao Informado.";
         $this->erro_campo = "fa10_i_margem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa10_i_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_programa"])){ 
       $sql  .= $virgula." fa10_i_programa = $this->fa10_i_programa ";
       $virgula = ",";
       if(trim($this->fa10_i_programa) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "fa10_i_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa10_i_codigo!=null){
       $sql .= " fa10_i_codigo = $this->fa10_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa10_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12488,'$this->fa10_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2179,12488,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_i_codigo'))."','$this->fa10_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_medicamento"]))
           $resac = db_query("insert into db_acount values($acount,2179,12489,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_i_medicamento'))."','$this->fa10_i_medicamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_quantidade"]))
           $resac = db_query("insert into db_acount values($acount,2179,12490,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_i_quantidade'))."','$this->fa10_i_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_controle"]))
           $resac = db_query("insert into db_acount values($acount,2179,12491,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_i_controle'))."','$this->fa10_i_controle',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_prazo"]))
           $resac = db_query("insert into db_acount values($acount,2179,12492,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_i_prazo'))."','$this->fa10_i_prazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_d_dataini"]))
           $resac = db_query("insert into db_acount values($acount,2179,12493,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_d_dataini'))."','$this->fa10_d_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_d_datafim"]))
           $resac = db_query("insert into db_acount values($acount,2179,12494,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_d_datafim'))."','$this->fa10_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_margem"]))
           $resac = db_query("insert into db_acount values($acount,2179,12495,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_i_margem'))."','$this->fa10_i_margem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa10_i_programa"]))
           $resac = db_query("insert into db_acount values($acount,2179,12496,'".AddSlashes(pg_result($resaco,$conresaco,'fa10_i_programa'))."','$this->fa10_i_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_controlemed nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa10_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_controlemed nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa10_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa10_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12488,'$fa10_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2179,12488,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2179,12489,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_i_medicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2179,12490,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2179,12491,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_i_controle'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2179,12492,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_i_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2179,12493,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2179,12494,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2179,12495,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_i_margem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2179,12496,'','".AddSlashes(pg_result($resaco,$iresaco,'fa10_i_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_controlemed
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa10_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa10_i_codigo = $fa10_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_controlemed nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa10_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_controlemed nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa10_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_controlemed";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_controlemed ";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = far_controlemed.fa10_i_medicamento";
     $sql .= "      inner join far_programa  on  far_programa.fa12_i_codigo = far_controlemed.fa10_i_programa";
     $sql .= "      inner join far_controle  on  far_controle.fa11_i_codigo = far_controlemed.fa10_i_controle";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
	 $sql .= "      inner join matunid on matunid.m61_codmatunid = matmater.m60_codmatunid";   
     $sql .= "      inner join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sql .= "      left join cgs_und  on  cgs_und.z01_i_cgsund = far_controle.fa11_i_cgsund";
	 $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($fa10_i_codigo!=null ){
         $sql2 .= " where far_controlemed.fa10_i_codigo = $fa10_i_codigo "; 
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
function sql_query_tipo ( $fa10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select distinct";
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
     $sql .= " from far_controlemed ";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = far_controlemed.fa10_i_medicamento";
     $sql .= "      inner join far_programa  on  far_programa.fa12_i_codigo = far_controlemed.fa10_i_programa";
     $sql .= "      inner join far_controle  on  far_controle.fa11_i_codigo = far_controlemed.fa10_i_controle";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
	 $sql .= "      inner join matunid on matunid.m61_codmatunid = matmater.m60_codmatunid";   
     $sql .= "      inner join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sql .= "      left join cgs_und  on  cgs_und.z01_i_cgsund = far_controle.fa11_i_cgsund";
	 $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql .= "      inner join far_listacontroladomed  on  far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed";
	 $sql .= "      inner join far_listacontrolado  on  far_listacontrolado.fa15_i_codigo = far_listacontroladomed.fa35_i_listacontrolado";
     $sql .= "      inner join far_listaprescricao  on  far_listaprescricao.fa21_i_listacontrolado = far_listacontrolado.fa15_i_codigo";
     $sql .= "      inner join far_prescricaomed  on  far_prescricaomed.fa31_i_prescricao = far_listaprescricao.fa21_i_prescricaomedica";
     $sql .= "      inner join far_prescricaomedica  on  far_prescricaomedica.fa20_i_codigo = far_prescricaomed.fa31_i_prescricao";
     $sql2 = "";
     if($dbwhere==""){
       if($fa10_i_codigo!=null ){
         $sql2 .= " where far_controlemed.fa10_i_codigo = $fa10_i_codigo "; 
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
   function sql_query_file ( $fa10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_controlemed ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa10_i_codigo!=null ){
         $sql2 .= " where far_controlemed.fa10_i_codigo = $fa10_i_codigo "; 
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