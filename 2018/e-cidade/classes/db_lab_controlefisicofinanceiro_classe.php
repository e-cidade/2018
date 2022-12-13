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

//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_controlefisicofinanceiro
class cl_lab_controlefisicofinanceiro { 
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
   var $la56_i_codigo = 0; 
   var $la56_i_laboratorio = 0; 
   var $la56_i_formaorganizacao = 0; 
   var $la56_i_depto = 0; 
   var $la56_i_exame = 0; 
   var $la56_i_grupo = 0; 
   var $la56_i_teto = 0; 
   var $la56_i_periodo = 0; 
   var $la56_d_ini_dia = null; 
   var $la56_d_ini_mes = null; 
   var $la56_d_ini_ano = null; 
   var $la56_d_ini = null; 
   var $la56_i_subgrupo = 0; 
   var $la56_d_fim_dia = null; 
   var $la56_d_fim_mes = null; 
   var $la56_d_fim_ano = null; 
   var $la56_d_fim = null; 
   var $la56_n_limite = 0; 
   var $la56_i_tipocontrole = 0; 
   var $la56_i_liberarequisicaosemsaldo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la56_i_codigo = int4 = Código 
                 la56_i_laboratorio = int4 = Laboratório 
                 la56_i_formaorganizacao = int4 = Forma de organização 
                 la56_i_depto = int4 = Departamento 
                 la56_i_exame = int4 = Exame 
                 la56_i_grupo = int4 = Grupo 
                 la56_i_teto = int4 = Teto 
                 la56_i_periodo = int4 = Período 
                 la56_d_ini = date = Início 
                 la56_i_subgrupo = int4 = Subgrupo 
                 la56_d_fim = date = Fim 
                 la56_n_limite = float4 = Limite 
                 la56_i_tipocontrole = int4 = Tipo de Controle Físico / Finaneiro 
                 la56_i_liberarequisicaosemsaldo = int4 = Liberar Requisição sem Saldo 
                 ";
   //funcao construtor da classe 
   function cl_lab_controlefisicofinanceiro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_controlefisicofinanceiro"); 
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
       $this->la56_i_codigo = ($this->la56_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_codigo"]:$this->la56_i_codigo);
       $this->la56_i_laboratorio = ($this->la56_i_laboratorio == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_laboratorio"]:$this->la56_i_laboratorio);
       $this->la56_i_formaorganizacao = ($this->la56_i_formaorganizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_formaorganizacao"]:$this->la56_i_formaorganizacao);
       $this->la56_i_depto = ($this->la56_i_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_depto"]:$this->la56_i_depto);
       $this->la56_i_exame = ($this->la56_i_exame == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_exame"]:$this->la56_i_exame);
       $this->la56_i_grupo = ($this->la56_i_grupo == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_grupo"]:$this->la56_i_grupo);
       $this->la56_i_teto = ($this->la56_i_teto == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_teto"]:$this->la56_i_teto);
       $this->la56_i_periodo = ($this->la56_i_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_periodo"]:$this->la56_i_periodo);
       if($this->la56_d_ini == ""){
         $this->la56_d_ini_dia = ($this->la56_d_ini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_d_ini_dia"]:$this->la56_d_ini_dia);
         $this->la56_d_ini_mes = ($this->la56_d_ini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_d_ini_mes"]:$this->la56_d_ini_mes);
         $this->la56_d_ini_ano = ($this->la56_d_ini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_d_ini_ano"]:$this->la56_d_ini_ano);
         if($this->la56_d_ini_dia != ""){
            $this->la56_d_ini = $this->la56_d_ini_ano."-".$this->la56_d_ini_mes."-".$this->la56_d_ini_dia;
         }
       }
       $this->la56_i_subgrupo = ($this->la56_i_subgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_subgrupo"]:$this->la56_i_subgrupo);
       if($this->la56_d_fim == ""){
         $this->la56_d_fim_dia = ($this->la56_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_d_fim_dia"]:$this->la56_d_fim_dia);
         $this->la56_d_fim_mes = ($this->la56_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_d_fim_mes"]:$this->la56_d_fim_mes);
         $this->la56_d_fim_ano = ($this->la56_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_d_fim_ano"]:$this->la56_d_fim_ano);
         if($this->la56_d_fim_dia != ""){
            $this->la56_d_fim = $this->la56_d_fim_ano."-".$this->la56_d_fim_mes."-".$this->la56_d_fim_dia;
         }
       }
       $this->la56_n_limite = ($this->la56_n_limite == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_n_limite"]:$this->la56_n_limite);
       $this->la56_i_tipocontrole = ($this->la56_i_tipocontrole == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_tipocontrole"]:$this->la56_i_tipocontrole);
       $this->la56_i_liberarequisicaosemsaldo = ($this->la56_i_liberarequisicaosemsaldo == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_liberarequisicaosemsaldo"]:$this->la56_i_liberarequisicaosemsaldo);
     }else{
       $this->la56_i_codigo = ($this->la56_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la56_i_codigo"]:$this->la56_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la56_i_codigo){ 
      $this->atualizacampos();
     if($this->la56_i_laboratorio == null ){ 
       $this->la56_i_laboratorio = "null";
     }
     if($this->la56_i_formaorganizacao == null ){ 
       $this->la56_i_formaorganizacao = "null";
     }
     if($this->la56_i_depto == null ){ 
       $this->la56_i_depto = "null";
     }
     if($this->la56_i_exame == null ){ 
       $this->la56_i_exame = "null";
     }
     if($this->la56_i_grupo == null ){ 
       $this->la56_i_grupo = "null";
     }
     if($this->la56_i_teto == null ){ 
       $this->erro_sql = " Campo Teto nao Informado.";
       $this->erro_campo = "la56_i_teto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la56_i_periodo == null ){ 
       $this->erro_sql = " Campo Período nao Informado.";
       $this->erro_campo = "la56_i_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la56_d_ini == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "la56_d_ini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la56_i_subgrupo == null ){ 
       $this->la56_i_subgrupo = "null";
     }
     if($this->la56_d_fim == null ){ 
       $this->la56_d_fim = "null";
     }
     if($this->la56_n_limite == null ){ 
       $this->erro_sql = " Campo Limite nao Informado.";
       $this->erro_campo = "la56_n_limite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la56_i_tipocontrole == null ){ 
       $this->erro_sql = " Campo Tipo de Controle Físico / Finaneiro nao Informado.";
       $this->erro_campo = "la56_i_tipocontrole";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la56_i_liberarequisicaosemsaldo == null ){ 
       $this->erro_sql = " Campo Liberar Requisição sem Saldo nao Informado.";
       $this->erro_campo = "la56_i_liberarequisicaosemsaldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la56_i_codigo == "" || $la56_i_codigo == null ){
       $result = db_query("select nextval('lab_controlefisicofinanceiro_la56_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_controlefisicofinanceiro_la56_i_codigo_seq do campo: la56_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la56_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_controlefisicofinanceiro_la56_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la56_i_codigo)){
         $this->erro_sql = " Campo la56_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la56_i_codigo = $la56_i_codigo; 
       }
     }
     if(($this->la56_i_codigo == null) || ($this->la56_i_codigo == "") ){ 
       $this->erro_sql = " Campo la56_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_controlefisicofinanceiro(
                                       la56_i_codigo 
                                      ,la56_i_laboratorio 
                                      ,la56_i_formaorganizacao 
                                      ,la56_i_depto 
                                      ,la56_i_exame 
                                      ,la56_i_grupo 
                                      ,la56_i_teto 
                                      ,la56_i_periodo 
                                      ,la56_d_ini 
                                      ,la56_i_subgrupo 
                                      ,la56_d_fim 
                                      ,la56_n_limite 
                                      ,la56_i_tipocontrole 
                                      ,la56_i_liberarequisicaosemsaldo 
                       )
                values (
                                $this->la56_i_codigo 
                               ,$this->la56_i_laboratorio 
                               ,$this->la56_i_formaorganizacao 
                               ,$this->la56_i_depto 
                               ,$this->la56_i_exame 
                               ,$this->la56_i_grupo 
                               ,$this->la56_i_teto 
                               ,$this->la56_i_periodo 
                               ,".($this->la56_d_ini == "null" || $this->la56_d_ini == ""?"null":"'".$this->la56_d_ini."'")." 
                               ,$this->la56_i_subgrupo 
                               ,".($this->la56_d_fim == "null" || $this->la56_d_fim == ""?"null":"'".$this->la56_d_fim."'")." 
                               ,$this->la56_n_limite 
                               ,$this->la56_i_tipocontrole 
                               ,$this->la56_i_liberarequisicaosemsaldo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_controlefisicofinanceiro ($this->la56_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_controlefisicofinanceiro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_controlefisicofinanceiro ($this->la56_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la56_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la56_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17692,'$this->la56_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3125,17692,'','".AddSlashes(pg_result($resaco,0,'la56_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17693,'','".AddSlashes(pg_result($resaco,0,'la56_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17694,'','".AddSlashes(pg_result($resaco,0,'la56_i_formaorganizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17695,'','".AddSlashes(pg_result($resaco,0,'la56_i_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17696,'','".AddSlashes(pg_result($resaco,0,'la56_i_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17703,'','".AddSlashes(pg_result($resaco,0,'la56_i_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17697,'','".AddSlashes(pg_result($resaco,0,'la56_i_teto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17699,'','".AddSlashes(pg_result($resaco,0,'la56_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17700,'','".AddSlashes(pg_result($resaco,0,'la56_d_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17708,'','".AddSlashes(pg_result($resaco,0,'la56_i_subgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17709,'','".AddSlashes(pg_result($resaco,0,'la56_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17715,'','".AddSlashes(pg_result($resaco,0,'la56_n_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17916,'','".AddSlashes(pg_result($resaco,0,'la56_i_tipocontrole'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3125,17917,'','".AddSlashes(pg_result($resaco,0,'la56_i_liberarequisicaosemsaldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la56_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_controlefisicofinanceiro set ";
     $virgula = "";
     if(trim($this->la56_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_codigo"])){ 
       $sql  .= $virgula." la56_i_codigo = $this->la56_i_codigo ";
       $virgula = ",";
       if(trim($this->la56_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la56_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la56_i_laboratorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_laboratorio"])){ 
        if(trim($this->la56_i_laboratorio)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la56_i_laboratorio"])){ 
           $this->la56_i_laboratorio = "0" ; 
        } 
       $sql  .= $virgula." la56_i_laboratorio = $this->la56_i_laboratorio ";
       $virgula = ",";
     }
     if(trim($this->la56_i_formaorganizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_formaorganizacao"])){ 
        if(trim($this->la56_i_formaorganizacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la56_i_formaorganizacao"])){ 
           $this->la56_i_formaorganizacao = "0" ; 
        } 
       $sql  .= $virgula." la56_i_formaorganizacao = $this->la56_i_formaorganizacao ";
       $virgula = ",";
     }
     if(trim($this->la56_i_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_depto"])){ 
        if(trim($this->la56_i_depto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la56_i_depto"])){ 
           $this->la56_i_depto = "0" ; 
        } 
       $sql  .= $virgula." la56_i_depto = $this->la56_i_depto ";
       $virgula = ",";
     }
     if(trim($this->la56_i_exame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_exame"])){ 
        if(trim($this->la56_i_exame)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la56_i_exame"])){ 
           $this->la56_i_exame = "0" ; 
        } 
       $sql  .= $virgula." la56_i_exame = $this->la56_i_exame ";
       $virgula = ",";
     }
     if(trim($this->la56_i_grupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_grupo"])){ 
        if(trim($this->la56_i_grupo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la56_i_grupo"])){ 
           $this->la56_i_grupo = "0" ; 
        } 
       $sql  .= $virgula." la56_i_grupo = $this->la56_i_grupo ";
       $virgula = ",";
     }
     if(trim($this->la56_i_teto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_teto"])){ 
       $sql  .= $virgula." la56_i_teto = $this->la56_i_teto ";
       $virgula = ",";
       if(trim($this->la56_i_teto) == null ){ 
         $this->erro_sql = " Campo Teto nao Informado.";
         $this->erro_campo = "la56_i_teto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la56_i_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_periodo"])){ 
       $sql  .= $virgula." la56_i_periodo = $this->la56_i_periodo ";
       $virgula = ",";
       if(trim($this->la56_i_periodo) == null ){ 
         $this->erro_sql = " Campo Período nao Informado.";
         $this->erro_campo = "la56_i_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la56_d_ini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_d_ini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la56_d_ini_dia"] !="") ){ 
       $sql  .= $virgula." la56_d_ini = '$this->la56_d_ini' ";
       $virgula = ",";
       if(trim($this->la56_d_ini) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "la56_d_ini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la56_d_ini_dia"])){ 
         $sql  .= $virgula." la56_d_ini = null ";
         $virgula = ",";
         if(trim($this->la56_d_ini) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "la56_d_ini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la56_i_subgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_subgrupo"])){ 
        if(trim($this->la56_i_subgrupo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la56_i_subgrupo"])){ 
           $this->la56_i_subgrupo = "0" ; 
        } 
       $sql  .= $virgula." la56_i_subgrupo = $this->la56_i_subgrupo ";
       $virgula = ",";
     }
     if(trim($this->la56_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la56_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." la56_d_fim = '$this->la56_d_fim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la56_d_fim_dia"])){ 
         $sql  .= $virgula." la56_d_fim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->la56_n_limite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_n_limite"])){ 
       $sql  .= $virgula." la56_n_limite = $this->la56_n_limite ";
       $virgula = ",";
       if(trim($this->la56_n_limite) == null ){ 
         $this->erro_sql = " Campo Limite nao Informado.";
         $this->erro_campo = "la56_n_limite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la56_i_tipocontrole)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_tipocontrole"])){ 
       $sql  .= $virgula." la56_i_tipocontrole = $this->la56_i_tipocontrole ";
       $virgula = ",";
       if(trim($this->la56_i_tipocontrole) == null ){ 
         $this->erro_sql = " Campo Tipo de Controle Físico / Finaneiro nao Informado.";
         $this->erro_campo = "la56_i_tipocontrole";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la56_i_liberarequisicaosemsaldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la56_i_liberarequisicaosemsaldo"])){ 
       $sql  .= $virgula." la56_i_liberarequisicaosemsaldo = $this->la56_i_liberarequisicaosemsaldo ";
       $virgula = ",";
       if(trim($this->la56_i_liberarequisicaosemsaldo) == null ){ 
         $this->erro_sql = " Campo Liberar Requisição sem Saldo nao Informado.";
         $this->erro_campo = "la56_i_liberarequisicaosemsaldo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la56_i_codigo!=null){
       $sql .= " la56_i_codigo = $this->la56_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la56_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17692,'$this->la56_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_codigo"]) || $this->la56_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3125,17692,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_codigo'))."','$this->la56_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_laboratorio"]) || $this->la56_i_laboratorio != "")
           $resac = db_query("insert into db_acount values($acount,3125,17693,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_laboratorio'))."','$this->la56_i_laboratorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_formaorganizacao"]) || $this->la56_i_formaorganizacao != "")
           $resac = db_query("insert into db_acount values($acount,3125,17694,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_formaorganizacao'))."','$this->la56_i_formaorganizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_depto"]) || $this->la56_i_depto != "")
           $resac = db_query("insert into db_acount values($acount,3125,17695,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_depto'))."','$this->la56_i_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_exame"]) || $this->la56_i_exame != "")
           $resac = db_query("insert into db_acount values($acount,3125,17696,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_exame'))."','$this->la56_i_exame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_grupo"]) || $this->la56_i_grupo != "")
           $resac = db_query("insert into db_acount values($acount,3125,17703,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_grupo'))."','$this->la56_i_grupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_teto"]) || $this->la56_i_teto != "")
           $resac = db_query("insert into db_acount values($acount,3125,17697,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_teto'))."','$this->la56_i_teto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_periodo"]) || $this->la56_i_periodo != "")
           $resac = db_query("insert into db_acount values($acount,3125,17699,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_periodo'))."','$this->la56_i_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_d_ini"]) || $this->la56_d_ini != "")
           $resac = db_query("insert into db_acount values($acount,3125,17700,'".AddSlashes(pg_result($resaco,$conresaco,'la56_d_ini'))."','$this->la56_d_ini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_subgrupo"]) || $this->la56_i_subgrupo != "")
           $resac = db_query("insert into db_acount values($acount,3125,17708,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_subgrupo'))."','$this->la56_i_subgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_d_fim"]) || $this->la56_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,3125,17709,'".AddSlashes(pg_result($resaco,$conresaco,'la56_d_fim'))."','$this->la56_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_n_limite"]) || $this->la56_n_limite != "")
           $resac = db_query("insert into db_acount values($acount,3125,17715,'".AddSlashes(pg_result($resaco,$conresaco,'la56_n_limite'))."','$this->la56_n_limite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_tipocontrole"]) || $this->la56_i_tipocontrole != "")
           $resac = db_query("insert into db_acount values($acount,3125,17916,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_tipocontrole'))."','$this->la56_i_tipocontrole',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la56_i_liberarequisicaosemsaldo"]) || $this->la56_i_liberarequisicaosemsaldo != "")
           $resac = db_query("insert into db_acount values($acount,3125,17917,'".AddSlashes(pg_result($resaco,$conresaco,'la56_i_liberarequisicaosemsaldo'))."','$this->la56_i_liberarequisicaosemsaldo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_controlefisicofinanceiro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la56_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_controlefisicofinanceiro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la56_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la56_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17692,'$la56_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3125,17692,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17693,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17694,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_formaorganizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17695,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17696,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17703,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17697,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_teto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17699,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17700,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_d_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17708,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_subgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17709,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17715,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_n_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17916,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_tipocontrole'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3125,17917,'','".AddSlashes(pg_result($resaco,$iresaco,'la56_i_liberarequisicaosemsaldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_controlefisicofinanceiro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la56_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la56_i_codigo = $la56_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_controlefisicofinanceiro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la56_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_controlefisicofinanceiro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la56_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_controlefisicofinanceiro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la56_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_controlefisicofinanceiro ";
     $sql .= "      left  join db_depart  on  db_depart.coddepto = lab_controlefisicofinanceiro.la56_i_depto";
     $sql .= "      left  join sau_grupo  on  sau_grupo.sd60_i_codigo = lab_controlefisicofinanceiro.la56_i_grupo";
     $sql .= "      left  join sau_subgrupo  on  sau_subgrupo.sd61_i_codigo = lab_controlefisicofinanceiro.la56_i_subgrupo";
     $sql .= "      left  join sau_formaorganizacao  on  sau_formaorganizacao.sd62_i_codigo = lab_controlefisicofinanceiro.la56_i_formaorganizacao";
     $sql .= "      left  join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_controlefisicofinanceiro.la56_i_laboratorio";
     $sql .= "      left  join lab_exame  on  lab_exame.la08_i_codigo = lab_controlefisicofinanceiro.la56_i_exame";
     $sql .= "      inner join lab_tipocontrolefisicofinanceiro  on  lab_tipocontrolefisicofinanceiro.la57_i_codigo = lab_controlefisicofinanceiro.la56_i_tipocontrole";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join sau_grupo  as a on   a.sd60_i_codigo = sau_subgrupo.sd61_i_grupo";
     $sql .= "      inner join sau_grupo  as b on   b.sd60_i_codigo = sau_formaorganizacao.sd62_i_grupo";
     $sql .= "      inner join sau_subgrupo  as c on   c.sd61_i_codigo = sau_formaorganizacao.sd62_i_subgrupo";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = lab_laboratorio.la02_i_turnoatend";
     $sql2 = "";
     if($dbwhere==""){
       if($la56_i_codigo!=null ){
         $sql2 .= " where lab_controlefisicofinanceiro.la56_i_codigo = $la56_i_codigo "; 
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
   function sql_query_file ( $la56_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_controlefisicofinanceiro ";
     $sql2 = "";
     if($dbwhere==""){
       if($la56_i_codigo!=null ){
         $sql2 .= " where lab_controlefisicofinanceiro.la56_i_codigo = $la56_i_codigo "; 
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
   function sql_query_controle ( $la56_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_controlefisicofinanceiro ";
     $sql .= "      left  join db_depart  on  db_depart.coddepto = lab_controlefisicofinanceiro.la56_i_depto";
     $sql .= "      left  join sau_grupo  on  sau_grupo.sd60_i_codigo = lab_controlefisicofinanceiro.la56_i_grupo";
     $sql .= "      left  join sau_subgrupo  on  sau_subgrupo.sd61_i_codigo = lab_controlefisicofinanceiro.la56_i_subgrupo";
     $sql .= "      left  join sau_formaorganizacao  on  sau_formaorganizacao.sd62_i_codigo = lab_controlefisicofinanceiro.la56_i_formaorganizacao";
     $sql .= "      left  join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_controlefisicofinanceiro.la56_i_laboratorio";
     $sql .= "      left  join lab_exame  on  lab_exame.la08_i_codigo = lab_controlefisicofinanceiro.la56_i_exame";
     $sql2 = "";
     if($dbwhere==""){
       if($la56_i_codigo!=null ){
         $sql2 .= " where lab_controlefisicofinanceiro.la56_i_codigo = $la56_i_codigo "; 
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