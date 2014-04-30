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

//MODULO: saude
//CLASSE DA ENTIDADE procedimentos
class cl_procedimentos { 
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
   var $sd09_i_codigo = 0; 
   var $sd09_c_grupoproc = null; 
   var $sd09_c_descr = null; 
   var $sd09_f_valor = 0; 
   var $sd09_b_pab = 'f'; 
   var $sd09_c_comp = null; 
   var $sd09_d_validade_dia = null; 
   var $sd09_d_validade_mes = null; 
   var $sd09_d_validade_ano = null; 
   var $sd09_d_validade = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd09_i_codigo = int4 = Código 
                 sd09_c_grupoproc = char(10) = Grupo de Procedimento 
                 sd09_c_descr = char(100) = Descrição 
                 sd09_f_valor = float4 = Valor 
                 sd09_b_pab = bool = Pab 
                 sd09_c_comp = char(200) = Complemento 
                 sd09_d_validade = date = Validade 
                 ";
   //funcao construtor da classe 
   function cl_procedimentos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procedimentos"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
         //echo "<script>location.href='".$this->pagina_retorno."'</script>";
         //echo "<script>
         //       parent.mo_camada('a2');
         //       parent.document.formaba.a2.disabled = false;
         //      </script>";
         echo "<script>
                  document.form1.sd09_i_codigo.value = $this->sd09_i_codigo;
                  parent.mo_camada('a2');
                  parent.document.formaba.a2.disabled = false;
                  parent.iframe_a2.document.location.href='sau1_procservicos001.php?sd19_i_procedimento=$this->sd09_i_codigo';
                  parent.document.formaba.a3.disabled = false;
                  parent.iframe_a3.document.location.href='sau1_proctipoatend001.php?sd20_i_procedimento=$this->sd09_i_codigo';
                  parent.document.formaba.a4.disabled = false;
                  parent.iframe_a4.document.location.href='sau1_procgrupoatend001.php?sd17_i_procedimento=$this->sd09_i_codigo';
                  parent.document.formaba.a5.disabled = false;
                  parent.iframe_a5.document.location.href='sau1_procfaixaetaria001.php?sd16_i_procedimento=$this->sd09_i_codigo';
                  parent.document.formaba.a6.disabled = false;
                  parent.iframe_a6.document.location.href='sau1_procespecialidades001.php?sd18_i_procedimento=$this->sd09_i_codigo';
               </script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->sd09_i_codigo = ($this->sd09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_i_codigo"]:$this->sd09_i_codigo);
       $this->sd09_c_grupoproc = ($this->sd09_c_grupoproc == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_c_grupoproc"]:$this->sd09_c_grupoproc);
       $this->sd09_c_descr = ($this->sd09_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_c_descr"]:$this->sd09_c_descr);
       $this->sd09_f_valor = ($this->sd09_f_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_f_valor"]:$this->sd09_f_valor);
       $this->sd09_b_pab = ($this->sd09_b_pab == false?@$GLOBALS["HTTP_POST_VARS"]["sd09_b_pab"]:$this->sd09_b_pab);
       $this->sd09_c_comp = ($this->sd09_c_comp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_c_comp"]:$this->sd09_c_comp);
       if($this->sd09_d_validade == ""){
         $this->sd09_d_validade_dia = ($this->sd09_d_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_d_validade_dia"]:$this->sd09_d_validade_dia);
         $this->sd09_d_validade_mes = ($this->sd09_d_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_d_validade_mes"]:$this->sd09_d_validade_mes);
         $this->sd09_d_validade_ano = ($this->sd09_d_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_d_validade_ano"]:$this->sd09_d_validade_ano);
         if($this->sd09_d_validade_dia != ""){
            $this->sd09_d_validade = $this->sd09_d_validade_ano."-".$this->sd09_d_validade_mes."-".$this->sd09_d_validade_dia;
         }
       }
     }else{
       $this->sd09_i_codigo = ($this->sd09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd09_i_codigo"]:$this->sd09_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd09_i_codigo){ 
      $this->atualizacampos();
     if($this->sd09_c_grupoproc == null ){ 
       $this->erro_sql = " Campo Grupo de Procedimento nao Informado.";
       $this->erro_campo = "sd09_c_grupoproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd09_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "sd09_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd09_f_valor == null ){ 
       $this->sd09_f_valor = "0";
     }
     if($this->sd09_b_pab == null ){ 
       $this->erro_sql = " Campo Pab nao Informado.";
       $this->erro_campo = "sd09_b_pab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd09_d_validade == null ){ 
       $this->sd09_d_validade = "null";
     }
     if($sd09_i_codigo == "" || $sd09_i_codigo == null ){
       $result = db_query("select nextval('procedimentos_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procedimentos_codigo_seq do campo: sd09_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd09_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procedimentos_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd09_i_codigo)){
         $this->erro_sql = " Campo sd09_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd09_i_codigo = $sd09_i_codigo; 
       }
     }
     if(($this->sd09_i_codigo == null) || ($this->sd09_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd09_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procedimentos(
                                       sd09_i_codigo 
                                      ,sd09_c_grupoproc 
                                      ,sd09_c_descr 
                                      ,sd09_f_valor 
                                      ,sd09_b_pab 
                                      ,sd09_c_comp 
                                      ,sd09_d_validade 
                       )
                values (
                                $this->sd09_i_codigo 
                               ,'$this->sd09_c_grupoproc' 
                               ,'$this->sd09_c_descr' 
                               ,$this->sd09_f_valor 
                               ,'$this->sd09_b_pab' 
                               ,'$this->sd09_c_comp' 
                               ,".($this->sd09_d_validade == "null" || $this->sd09_d_validade == ""?"null":"'".$this->sd09_d_validade."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimentos ($this->sd09_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimentos ($this->sd09_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd09_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd09_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,100007,'$this->sd09_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,100003,100007,'','".AddSlashes(pg_result($resaco,0,'sd09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100003,100008,'','".AddSlashes(pg_result($resaco,0,'sd09_c_grupoproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100003,100009,'','".AddSlashes(pg_result($resaco,0,'sd09_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100003,100010,'','".AddSlashes(pg_result($resaco,0,'sd09_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100003,100011,'','".AddSlashes(pg_result($resaco,0,'sd09_b_pab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100003,100021,'','".AddSlashes(pg_result($resaco,0,'sd09_c_comp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100003,100022,'','".AddSlashes(pg_result($resaco,0,'sd09_d_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd09_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update procedimentos set ";
     $virgula = "";
     if(trim($this->sd09_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd09_i_codigo"])){ 
       $sql  .= $virgula." sd09_i_codigo = $this->sd09_i_codigo ";
       $virgula = ",";
       if(trim($this->sd09_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd09_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd09_c_grupoproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd09_c_grupoproc"])){ 
       $sql  .= $virgula." sd09_c_grupoproc = '$this->sd09_c_grupoproc' ";
       $virgula = ",";
       if(trim($this->sd09_c_grupoproc) == null ){ 
         $this->erro_sql = " Campo Grupo de Procedimento nao Informado.";
         $this->erro_campo = "sd09_c_grupoproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd09_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd09_c_descr"])){ 
       $sql  .= $virgula." sd09_c_descr = '$this->sd09_c_descr' ";
       $virgula = ",";
       if(trim($this->sd09_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "sd09_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd09_f_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd09_f_valor"])){ 
        if(trim($this->sd09_f_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd09_f_valor"])){ 
           $this->sd09_f_valor = "0" ; 
        } 
       $sql  .= $virgula." sd09_f_valor = $this->sd09_f_valor ";
       $virgula = ",";
     }
     if(trim($this->sd09_b_pab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd09_b_pab"])){ 
       $sql  .= $virgula." sd09_b_pab = '$this->sd09_b_pab' ";
       $virgula = ",";
       if(trim($this->sd09_b_pab) == null ){ 
         $this->erro_sql = " Campo Pab nao Informado.";
         $this->erro_campo = "sd09_b_pab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd09_c_comp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd09_c_comp"])){ 
       $sql  .= $virgula." sd09_c_comp = '$this->sd09_c_comp' ";
       $virgula = ",";
     }
     if(trim($this->sd09_d_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd09_d_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd09_d_validade_dia"] !="") ){ 
       $sql  .= $virgula." sd09_d_validade = '$this->sd09_d_validade' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd09_d_validade_dia"])){ 
         $sql  .= $virgula." sd09_d_validade = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($sd09_i_codigo!=null){
       $sql .= " sd09_i_codigo = $this->sd09_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd09_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100007,'$this->sd09_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd09_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,100003,100007,'".AddSlashes(pg_result($resaco,$conresaco,'sd09_i_codigo'))."','$this->sd09_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd09_c_grupoproc"]))
           $resac = db_query("insert into db_acount values($acount,100003,100008,'".AddSlashes(pg_result($resaco,$conresaco,'sd09_c_grupoproc'))."','$this->sd09_c_grupoproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd09_c_descr"]))
           $resac = db_query("insert into db_acount values($acount,100003,100009,'".AddSlashes(pg_result($resaco,$conresaco,'sd09_c_descr'))."','$this->sd09_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd09_f_valor"]))
           $resac = db_query("insert into db_acount values($acount,100003,100010,'".AddSlashes(pg_result($resaco,$conresaco,'sd09_f_valor'))."','$this->sd09_f_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd09_b_pab"]))
           $resac = db_query("insert into db_acount values($acount,100003,100011,'".AddSlashes(pg_result($resaco,$conresaco,'sd09_b_pab'))."','$this->sd09_b_pab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd09_c_comp"]))
           $resac = db_query("insert into db_acount values($acount,100003,100021,'".AddSlashes(pg_result($resaco,$conresaco,'sd09_c_comp'))."','$this->sd09_c_comp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd09_d_validade"]))
           $resac = db_query("insert into db_acount values($acount,100003,100022,'".AddSlashes(pg_result($resaco,$conresaco,'sd09_d_validade'))."','$this->sd09_d_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd09_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd09_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd09_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100007,'$sd09_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,100003,100007,'','".AddSlashes(pg_result($resaco,$iresaco,'sd09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100003,100008,'','".AddSlashes(pg_result($resaco,$iresaco,'sd09_c_grupoproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100003,100009,'','".AddSlashes(pg_result($resaco,$iresaco,'sd09_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100003,100010,'','".AddSlashes(pg_result($resaco,$iresaco,'sd09_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100003,100011,'','".AddSlashes(pg_result($resaco,$iresaco,'sd09_b_pab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100003,100021,'','".AddSlashes(pg_result($resaco,$iresaco,'sd09_c_comp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100003,100022,'','".AddSlashes(pg_result($resaco,$iresaco,'sd09_d_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procedimentos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd09_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd09_i_codigo = $sd09_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd09_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd09_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:procedimentos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procedimentos ";
     $sql .= "      left join grupoproc  on  grupoproc.sd11_c_codigo = procedimentos.sd09_c_grupoproc";
     $sql2 = "";
     if($dbwhere==""){
       if($sd09_i_codigo!=null ){
         $sql2 .= " where procedimentos.sd09_i_codigo = $sd09_i_codigo "; 
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
   function sql_query_file ( $sd09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procedimentos ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd09_i_codigo!=null ){
         $sql2 .= " where procedimentos.sd09_i_codigo = $sd09_i_codigo "; 
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