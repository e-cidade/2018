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

//MODULO: educação
//CLASSE DA ENTIDADE alunopassagemqtd
class cl_alunopassagemqtd { 
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
   var $ed227_i_usuario = 0; 
   var $ed227_i_alunopassagem = 0; 
   var $ed227_i_valorpassagem = 0; 
   var $ed227_i_qtde = 0; 
   var $ed227_d_datainicio_dia = null; 
   var $ed227_d_datainicio_mes = null; 
   var $ed227_d_datainicio_ano = null; 
   var $ed227_d_datainicio = null; 
   var $ed227_d_datafim_dia = null; 
   var $ed227_d_datafim_mes = null; 
   var $ed227_d_datafim_ano = null; 
   var $ed227_d_datafim = null; 
   var $ed227_d_datacad_dia = null; 
   var $ed227_d_datacad_mes = null; 
   var $ed227_d_datacad_ano = null; 
   var $ed227_d_datacad = null; 
   var $ed227_i_codigo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed227_i_usuario = int8 = Usuário 
                 ed227_i_alunopassagem = int8 = Aluno 
                 ed227_i_valorpassagem = int8 = Valor da Passagem 
                 ed227_i_qtde = int4 = Qtd. de Passagens 
                 ed227_d_datainicio = date = Data Inicial 
                 ed227_d_datafim = date = Data Final 
                 ed227_d_datacad = date = Data de Cadastro 
                 ed227_i_codigo = int4 = Código 
                 ";
   //funcao construtor da classe 
   function cl_alunopassagemqtd() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunopassagemqtd"); 
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
       $this->ed227_i_usuario = ($this->ed227_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_i_usuario"]:$this->ed227_i_usuario);
       $this->ed227_i_alunopassagem = ($this->ed227_i_alunopassagem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_i_alunopassagem"]:$this->ed227_i_alunopassagem);
       $this->ed227_i_valorpassagem = ($this->ed227_i_valorpassagem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_i_valorpassagem"]:$this->ed227_i_valorpassagem);
       $this->ed227_i_qtde = ($this->ed227_i_qtde == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_i_qtde"]:$this->ed227_i_qtde);
       if($this->ed227_d_datainicio == ""){
         $this->ed227_d_datainicio_dia = ($this->ed227_d_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datainicio_dia"]:$this->ed227_d_datainicio_dia);
         $this->ed227_d_datainicio_mes = ($this->ed227_d_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datainicio_mes"]:$this->ed227_d_datainicio_mes);
         $this->ed227_d_datainicio_ano = ($this->ed227_d_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datainicio_ano"]:$this->ed227_d_datainicio_ano);
         if($this->ed227_d_datainicio_dia != ""){
            $this->ed227_d_datainicio = $this->ed227_d_datainicio_ano."-".$this->ed227_d_datainicio_mes."-".$this->ed227_d_datainicio_dia;
         }
       }
       if($this->ed227_d_datafim == ""){
         $this->ed227_d_datafim_dia = ($this->ed227_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datafim_dia"]:$this->ed227_d_datafim_dia);
         $this->ed227_d_datafim_mes = ($this->ed227_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datafim_mes"]:$this->ed227_d_datafim_mes);
         $this->ed227_d_datafim_ano = ($this->ed227_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datafim_ano"]:$this->ed227_d_datafim_ano);
         if($this->ed227_d_datafim_dia != ""){
            $this->ed227_d_datafim = $this->ed227_d_datafim_ano."-".$this->ed227_d_datafim_mes."-".$this->ed227_d_datafim_dia;
         }
       }
       if($this->ed227_d_datacad == ""){
         $this->ed227_d_datacad_dia = ($this->ed227_d_datacad_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datacad_dia"]:$this->ed227_d_datacad_dia);
         $this->ed227_d_datacad_mes = ($this->ed227_d_datacad_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datacad_mes"]:$this->ed227_d_datacad_mes);
         $this->ed227_d_datacad_ano = ($this->ed227_d_datacad_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_d_datacad_ano"]:$this->ed227_d_datacad_ano);
         if($this->ed227_d_datacad_dia != ""){
            $this->ed227_d_datacad = $this->ed227_d_datacad_ano."-".$this->ed227_d_datacad_mes."-".$this->ed227_d_datacad_dia;
         }
       }
       $this->ed227_i_codigo = ($this->ed227_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_i_codigo"]:$this->ed227_i_codigo);
     }else{
       $this->ed227_i_codigo = ($this->ed227_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed227_i_codigo"]:$this->ed227_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed227_i_codigo){ 
      $this->atualizacampos();
     if($this->ed227_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed227_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed227_i_alunopassagem == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed227_i_alunopassagem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed227_i_valorpassagem == null ){ 
       $this->erro_sql = " Campo Valor da Passagem nao Informado.";
       $this->erro_campo = "ed227_i_valorpassagem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed227_i_qtde == null ){ 
       $this->erro_sql = " Campo Qtd. de Passagens nao Informado.";
       $this->erro_campo = "ed227_i_qtde";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed227_d_datainicio == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ed227_d_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed227_d_datafim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ed227_d_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed227_d_datacad == null ){ 
       $this->erro_sql = " Campo Data de Cadastro nao Informado.";
       $this->erro_campo = "ed227_d_datacad_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed227_i_codigo == "" || $ed227_i_codigo == null ){
       $result = db_query("select nextval('alunopassagemqtd_ed227_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunopassagemqtd_ed227_i_codigo_seq do campo: ed227_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed227_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from alunopassagemqtd_ed227_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed227_i_codigo)){
         $this->erro_sql = " Campo ed227_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed227_i_codigo = $ed227_i_codigo; 
       }
     }
     if(($this->ed227_i_codigo == null) || ($this->ed227_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed227_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunopassagemqtd(
                                       ed227_i_usuario 
                                      ,ed227_i_alunopassagem 
                                      ,ed227_i_valorpassagem 
                                      ,ed227_i_qtde 
                                      ,ed227_d_datainicio 
                                      ,ed227_d_datafim 
                                      ,ed227_d_datacad 
                                      ,ed227_i_codigo 
                       )
                values (
                                $this->ed227_i_usuario 
                               ,$this->ed227_i_alunopassagem 
                               ,$this->ed227_i_valorpassagem 
                               ,$this->ed227_i_qtde 
                               ,".($this->ed227_d_datainicio == "null" || $this->ed227_d_datainicio == ""?"null":"'".$this->ed227_d_datainicio."'")." 
                               ,".($this->ed227_d_datafim == "null" || $this->ed227_d_datafim == ""?"null":"'".$this->ed227_d_datafim."'")." 
                               ,".($this->ed227_d_datacad == "null" || $this->ed227_d_datacad == ""?"null":"'".$this->ed227_d_datacad."'")." 
                               ,$this->ed227_i_codigo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Qtde de Passagens por Aluno ($this->ed227_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Qtde de Passagens por Aluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Qtde de Passagens por Aluno ($this->ed227_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed227_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed227_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11282,'$this->ed227_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1949,11333,'','".AddSlashes(pg_result($resaco,0,'ed227_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1949,11334,'','".AddSlashes(pg_result($resaco,0,'ed227_i_alunopassagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1949,11335,'','".AddSlashes(pg_result($resaco,0,'ed227_i_valorpassagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1949,11336,'','".AddSlashes(pg_result($resaco,0,'ed227_i_qtde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1949,11337,'','".AddSlashes(pg_result($resaco,0,'ed227_d_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1949,11338,'','".AddSlashes(pg_result($resaco,0,'ed227_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1949,11339,'','".AddSlashes(pg_result($resaco,0,'ed227_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1949,11282,'','".AddSlashes(pg_result($resaco,0,'ed227_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed227_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update alunopassagemqtd set ";
     $virgula = "";
     if(trim($this->ed227_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_usuario"])){ 
       $sql  .= $virgula." ed227_i_usuario = $this->ed227_i_usuario ";
       $virgula = ",";
       if(trim($this->ed227_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed227_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed227_i_alunopassagem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_alunopassagem"])){ 
       $sql  .= $virgula." ed227_i_alunopassagem = $this->ed227_i_alunopassagem ";
       $virgula = ",";
       if(trim($this->ed227_i_alunopassagem) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed227_i_alunopassagem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed227_i_valorpassagem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_valorpassagem"])){ 
       $sql  .= $virgula." ed227_i_valorpassagem = $this->ed227_i_valorpassagem ";
       $virgula = ",";
       if(trim($this->ed227_i_valorpassagem) == null ){ 
         $this->erro_sql = " Campo Valor da Passagem nao Informado.";
         $this->erro_campo = "ed227_i_valorpassagem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed227_i_qtde)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_qtde"])){ 
       $sql  .= $virgula." ed227_i_qtde = $this->ed227_i_qtde ";
       $virgula = ",";
       if(trim($this->ed227_i_qtde) == null ){ 
         $this->erro_sql = " Campo Qtd. de Passagens nao Informado.";
         $this->erro_campo = "ed227_i_qtde";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed227_d_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed227_d_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." ed227_d_datainicio = '$this->ed227_d_datainicio' ";
       $virgula = ",";
       if(trim($this->ed227_d_datainicio) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ed227_d_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datainicio_dia"])){ 
         $sql  .= $virgula." ed227_d_datainicio = null ";
         $virgula = ",";
         if(trim($this->ed227_d_datainicio) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ed227_d_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed227_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed227_d_datafim_dia"] !="") ){ 
       $sql  .= $virgula." ed227_d_datafim = '$this->ed227_d_datafim' ";
       $virgula = ",";
       if(trim($this->ed227_d_datafim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ed227_d_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datafim_dia"])){ 
         $sql  .= $virgula." ed227_d_datafim = null ";
         $virgula = ",";
         if(trim($this->ed227_d_datafim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ed227_d_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed227_d_datacad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datacad_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed227_d_datacad_dia"] !="") ){ 
       $sql  .= $virgula." ed227_d_datacad = '$this->ed227_d_datacad' ";
       $virgula = ",";
       if(trim($this->ed227_d_datacad) == null ){ 
         $this->erro_sql = " Campo Data de Cadastro nao Informado.";
         $this->erro_campo = "ed227_d_datacad_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datacad_dia"])){ 
         $sql  .= $virgula." ed227_d_datacad = null ";
         $virgula = ",";
         if(trim($this->ed227_d_datacad) == null ){ 
           $this->erro_sql = " Campo Data de Cadastro nao Informado.";
           $this->erro_campo = "ed227_d_datacad_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed227_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_codigo"])){ 
       $sql  .= $virgula." ed227_i_codigo = $this->ed227_i_codigo ";
       $virgula = ",";
       if(trim($this->ed227_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed227_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed227_i_codigo!=null){
       $sql .= " ed227_i_codigo = $this->ed227_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed227_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11282,'$this->ed227_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1949,11333,'".AddSlashes(pg_result($resaco,$conresaco,'ed227_i_usuario'))."','$this->ed227_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_alunopassagem"]))
           $resac = db_query("insert into db_acount values($acount,1949,11334,'".AddSlashes(pg_result($resaco,$conresaco,'ed227_i_alunopassagem'))."','$this->ed227_i_alunopassagem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_valorpassagem"]))
           $resac = db_query("insert into db_acount values($acount,1949,11335,'".AddSlashes(pg_result($resaco,$conresaco,'ed227_i_valorpassagem'))."','$this->ed227_i_valorpassagem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_qtde"]))
           $resac = db_query("insert into db_acount values($acount,1949,11336,'".AddSlashes(pg_result($resaco,$conresaco,'ed227_i_qtde'))."','$this->ed227_i_qtde',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datainicio"]))
           $resac = db_query("insert into db_acount values($acount,1949,11337,'".AddSlashes(pg_result($resaco,$conresaco,'ed227_d_datainicio'))."','$this->ed227_d_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datafim"]))
           $resac = db_query("insert into db_acount values($acount,1949,11338,'".AddSlashes(pg_result($resaco,$conresaco,'ed227_d_datafim'))."','$this->ed227_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_d_datacad"]))
           $resac = db_query("insert into db_acount values($acount,1949,11339,'".AddSlashes(pg_result($resaco,$conresaco,'ed227_d_datacad'))."','$this->ed227_d_datacad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed227_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1949,11282,'".AddSlashes(pg_result($resaco,$conresaco,'ed227_i_codigo'))."','$this->ed227_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Qtde de Passagens por Aluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed227_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Qtde de Passagens por Aluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed227_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed227_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed227_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed227_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11282,'$ed227_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1949,11333,'','".AddSlashes(pg_result($resaco,$iresaco,'ed227_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1949,11334,'','".AddSlashes(pg_result($resaco,$iresaco,'ed227_i_alunopassagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1949,11335,'','".AddSlashes(pg_result($resaco,$iresaco,'ed227_i_valorpassagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1949,11336,'','".AddSlashes(pg_result($resaco,$iresaco,'ed227_i_qtde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1949,11337,'','".AddSlashes(pg_result($resaco,$iresaco,'ed227_d_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1949,11338,'','".AddSlashes(pg_result($resaco,$iresaco,'ed227_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1949,11339,'','".AddSlashes(pg_result($resaco,$iresaco,'ed227_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1949,11282,'','".AddSlashes(pg_result($resaco,$iresaco,'ed227_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunopassagemqtd
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed227_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed227_i_codigo = $ed227_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Qtde de Passagens por Aluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed227_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Qtde de Passagens por Aluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed227_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed227_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunopassagemqtd";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed227_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunopassagemqtd ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = alunopassagemqtd.ed227_i_usuario";
     $sql .= "      inner join alunopassagem  on  alunopassagem.ed215_i_codigo = alunopassagemqtd.ed227_i_alunopassagem";
     $sql .= "      inner join valorpassagem  on  valorpassagem.ed230_i_codigo = alunopassagemqtd.ed227_i_valorpassagem";
     $sql .= "      inner join linha  on  linha.ed217_i_codigo = alunopassagem.ed215_i_linha";
     $sql .= "      inner join aluno   on   aluno.ed47_i_codigo = alunopassagem.ed215_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed227_i_codigo!=null ){
         $sql2 .= " where alunopassagemqtd.ed227_i_codigo = $ed227_i_codigo "; 
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
   function sql_query_file ( $ed227_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunopassagemqtd ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed227_i_codigo!=null ){
         $sql2 .= " where alunopassagemqtd.ed227_i_codigo = $ed227_i_codigo "; 
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