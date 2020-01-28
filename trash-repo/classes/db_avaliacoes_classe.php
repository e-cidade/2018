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
//CLASSE DA ENTIDADE avaliacoes
class cl_avaliacoes { 
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
   var $ed13_i_codigo = 0; 
   var $ed13_i_disciplinas = 0; 
   var $ed13_i_periodo = 0; 
   var $ed13_i_turma = 0; 
   var $ed13_d_data_dia = null; 
   var $ed13_d_data_mes = null; 
   var $ed13_d_data_ano = null; 
   var $ed13_d_data = null; 
   var $ed13_f_valor = 0; 
   var $ed13_c_descr = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed13_i_codigo = int8 = Código 
                 ed13_i_disciplinas = int8 = Codigo da Disciplina 
                 ed13_i_periodo = int8 = Período 
                 ed13_i_turma = int8 = Turma 
                 ed13_d_data = date = Data 
                 ed13_f_valor = float8 = Valor 
                 ed13_c_descr = char(50) = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_avaliacoes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacoes"); 
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
       $this->ed13_i_codigo = ($this->ed13_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_i_codigo"]:$this->ed13_i_codigo);
       $this->ed13_i_disciplinas = ($this->ed13_i_disciplinas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_i_disciplinas"]:$this->ed13_i_disciplinas);
       $this->ed13_i_periodo = ($this->ed13_i_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_i_periodo"]:$this->ed13_i_periodo);
       $this->ed13_i_turma = ($this->ed13_i_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_i_turma"]:$this->ed13_i_turma);
       if($this->ed13_d_data == ""){
         $this->ed13_d_data_dia = ($this->ed13_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_d_data_dia"]:$this->ed13_d_data_dia);
         $this->ed13_d_data_mes = ($this->ed13_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_d_data_mes"]:$this->ed13_d_data_mes);
         $this->ed13_d_data_ano = ($this->ed13_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_d_data_ano"]:$this->ed13_d_data_ano);
         if($this->ed13_d_data_dia != ""){
            $this->ed13_d_data = $this->ed13_d_data_ano."-".$this->ed13_d_data_mes."-".$this->ed13_d_data_dia;
         }
       }
       $this->ed13_f_valor = ($this->ed13_f_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_f_valor"]:$this->ed13_f_valor);
       $this->ed13_c_descr = ($this->ed13_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_c_descr"]:$this->ed13_c_descr);
     }else{
       $this->ed13_i_codigo = ($this->ed13_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed13_i_codigo"]:$this->ed13_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed13_i_codigo){ 
      $this->atualizacampos();
     if($this->ed13_i_disciplinas == null ){ 
       $this->erro_sql = " Campo Codigo da Disciplina nao Informado.";
       $this->erro_campo = "ed13_i_disciplinas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed13_i_periodo == null ){ 
       $this->erro_sql = " Campo Período nao Informado.";
       $this->erro_campo = "ed13_i_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed13_i_turma == null ){ 
       $this->erro_sql = " Campo Turma nao Informado.";
       $this->erro_campo = "ed13_i_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed13_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed13_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed13_f_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "ed13_f_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed13_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed13_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed13_i_codigo == "" || $ed13_i_codigo == null ){
       $result = @pg_query("select nextval('avaliacoes_ed13_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacoes_ed13_i_codigo_seq do campo: ed13_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed13_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from avaliacoes_ed13_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed13_i_codigo)){
         $this->erro_sql = " Campo ed13_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed13_i_codigo = $ed13_i_codigo; 
       }
     }
     if(($this->ed13_i_codigo == null) || ($this->ed13_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed13_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacoes(
                                       ed13_i_codigo 
                                      ,ed13_i_disciplinas 
                                      ,ed13_i_periodo 
                                      ,ed13_i_turma 
                                      ,ed13_d_data 
                                      ,ed13_f_valor 
                                      ,ed13_c_descr 
                       )
                values (
                                $this->ed13_i_codigo 
                               ,$this->ed13_i_disciplinas 
                               ,$this->ed13_i_periodo 
                               ,$this->ed13_i_turma 
                               ,".($this->ed13_d_data == "null" || $this->ed13_d_data == ""?"null":"'".$this->ed13_d_data."'")." 
                               ,$this->ed13_f_valor 
                               ,'$this->ed13_c_descr' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliações ($this->ed13_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliações ($this->ed13_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed13_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed13_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006014,'$this->ed13_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006005,1006014,'','".AddSlashes(pg_result($resaco,0,'ed13_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006005,1006048,'','".AddSlashes(pg_result($resaco,0,'ed13_i_disciplinas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006005,1006050,'','".AddSlashes(pg_result($resaco,0,'ed13_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006005,1006314,'','".AddSlashes(pg_result($resaco,0,'ed13_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006005,1006015,'','".AddSlashes(pg_result($resaco,0,'ed13_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006005,1006043,'','".AddSlashes(pg_result($resaco,0,'ed13_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006005,1006017,'','".AddSlashes(pg_result($resaco,0,'ed13_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed13_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update avaliacoes set ";
     $virgula = "";
     if(trim($this->ed13_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed13_i_codigo"])){ 
       $sql  .= $virgula." ed13_i_codigo = $this->ed13_i_codigo ";
       $virgula = ",";
       if(trim($this->ed13_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed13_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed13_i_disciplinas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed13_i_disciplinas"])){ 
       $sql  .= $virgula." ed13_i_disciplinas = $this->ed13_i_disciplinas ";
       $virgula = ",";
       if(trim($this->ed13_i_disciplinas) == null ){ 
         $this->erro_sql = " Campo Codigo da Disciplina nao Informado.";
         $this->erro_campo = "ed13_i_disciplinas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed13_i_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed13_i_periodo"])){ 
       $sql  .= $virgula." ed13_i_periodo = $this->ed13_i_periodo ";
       $virgula = ",";
       if(trim($this->ed13_i_periodo) == null ){ 
         $this->erro_sql = " Campo Período nao Informado.";
         $this->erro_campo = "ed13_i_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed13_i_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed13_i_turma"])){ 
       $sql  .= $virgula." ed13_i_turma = $this->ed13_i_turma ";
       $virgula = ",";
       if(trim($this->ed13_i_turma) == null ){ 
         $this->erro_sql = " Campo Turma nao Informado.";
         $this->erro_campo = "ed13_i_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed13_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed13_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed13_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed13_d_data = '$this->ed13_d_data' ";
       $virgula = ",";
       if(trim($this->ed13_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed13_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed13_d_data_dia"])){ 
         $sql  .= $virgula." ed13_d_data = null ";
         $virgula = ",";
         if(trim($this->ed13_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed13_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed13_f_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed13_f_valor"])){ 
       $sql  .= $virgula." ed13_f_valor = $this->ed13_f_valor ";
       $virgula = ",";
       if(trim($this->ed13_f_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "ed13_f_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed13_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed13_c_descr"])){ 
       $sql  .= $virgula." ed13_c_descr = '$this->ed13_c_descr' ";
       $virgula = ",";
       if(trim($this->ed13_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed13_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed13_i_codigo!=null){
       $sql .= " ed13_i_codigo = $this->ed13_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed13_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006014,'$this->ed13_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed13_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006005,1006014,'".AddSlashes(pg_result($resaco,$conresaco,'ed13_i_codigo'))."','$this->ed13_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed13_i_disciplinas"]))
           $resac = pg_query("insert into db_acount values($acount,1006005,1006048,'".AddSlashes(pg_result($resaco,$conresaco,'ed13_i_disciplinas'))."','$this->ed13_i_disciplinas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed13_i_periodo"]))
           $resac = pg_query("insert into db_acount values($acount,1006005,1006050,'".AddSlashes(pg_result($resaco,$conresaco,'ed13_i_periodo'))."','$this->ed13_i_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed13_i_turma"]))
           $resac = pg_query("insert into db_acount values($acount,1006005,1006314,'".AddSlashes(pg_result($resaco,$conresaco,'ed13_i_turma'))."','$this->ed13_i_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed13_d_data"]))
           $resac = pg_query("insert into db_acount values($acount,1006005,1006015,'".AddSlashes(pg_result($resaco,$conresaco,'ed13_d_data'))."','$this->ed13_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed13_f_valor"]))
           $resac = pg_query("insert into db_acount values($acount,1006005,1006043,'".AddSlashes(pg_result($resaco,$conresaco,'ed13_f_valor'))."','$this->ed13_f_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed13_c_descr"]))
           $resac = pg_query("insert into db_acount values($acount,1006005,1006017,'".AddSlashes(pg_result($resaco,$conresaco,'ed13_c_descr'))."','$this->ed13_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed13_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed13_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed13_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006014,'$ed13_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006005,1006014,'','".AddSlashes(pg_result($resaco,$iresaco,'ed13_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006005,1006048,'','".AddSlashes(pg_result($resaco,$iresaco,'ed13_i_disciplinas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006005,1006050,'','".AddSlashes(pg_result($resaco,$iresaco,'ed13_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006005,1006314,'','".AddSlashes(pg_result($resaco,$iresaco,'ed13_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006005,1006015,'','".AddSlashes(pg_result($resaco,$iresaco,'ed13_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006005,1006043,'','".AddSlashes(pg_result($resaco,$iresaco,'ed13_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006005,1006017,'','".AddSlashes(pg_result($resaco,$iresaco,'ed13_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from avaliacoes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed13_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed13_i_codigo = $ed13_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed13_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed13_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacoes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed13_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacoes ";
     $sql .= "      inner join turmas  on  turmas.ed05_i_codigo = avaliacoes.ed13_i_turma";
     $sql .= "      inner join disciplinas  on  disciplinas.ed27_i_codigo = avaliacoes.ed13_i_disciplinas";
     $sql .= "      inner join periodos  on  periodos.ed23_i_codigo = avaliacoes.ed13_i_periodo";
     $sql .= "      inner join escolas  on  escolas.ed02_i_codigo = turmas.ed05_i_escola";
     $sql .= "      inner join series  on  series.ed03_i_codigo = turmas.ed05_i_serie";
     $sql .= "      inner join turnos  on  turnos.ed10_i_codigo = turmas.ed05_i_turno";
     $sql .= "      inner join anoletivo  on  anoletivo.ed28_i_codigo = periodos.ed23_i_anoletivo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed13_i_codigo!=null ){
         $sql2 .= " where avaliacoes.ed13_i_codigo = $ed13_i_codigo "; 
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
   function sql_query_file ( $ed13_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacoes ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed13_i_codigo!=null ){
         $sql2 .= " where avaliacoes.ed13_i_codigo = $ed13_i_codigo "; 
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